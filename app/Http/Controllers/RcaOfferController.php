<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\RcaOfferLog;
use App\Models\RcaPolicyLog;
use App\Models\RcaCustomer;
use App\Mail\RcaOfferMail;

class RcaOfferController extends Controller
{
    public function create(Request $request)
    {
        return view('rca.offer-form', [
            'insurers' => config('rca_insurers'),
        ]);
    }

    public function store(Request $request)
    {
        set_time_limit(180);

        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'term_time' => ['required', 'integer', 'between:1,12'],
            'installment_count' => ['required', 'integer', 'in:1,2,4,12'],
            'commission_percent_limit' => ['required', 'numeric', 'between:0,100'],

            'last_name' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'tax_id' => ['required', 'digits:13'],
            'birthdate' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:m,f'],
            'email' => ['required', 'email'],
            'mobile_number' => ['required', 'string'],

            'id_type' => ['required', 'in:CI,PASSPORT'],
            'id_number' => ['required', 'string'],
            'id_issue_authority' => ['required', 'string'],
            'id_issue_date' => ['required', 'date'],
            'driving_license_issue_date' => ['required', 'date'],

            'country' => ['required', 'string', 'size:2'],
            'county' => ['required', 'string'],
            'city' => ['required', 'string'],
            'city_code' => ['required', 'integer'],
            'street' => ['required', 'string'],
            'house_number' => ['nullable', 'string'],
            'building' => ['nullable', 'string'],
            'staircase' => ['nullable', 'string'],
            'apartment' => ['nullable', 'string'],
            'floor' => ['nullable', 'string'],
            'postcode' => ['nullable', 'string'],

            'license_plate' => ['required', 'string'],
            'registration_type' => ['required', 'in:registered,recorded,temporaryRegistered,temporaryRecorded'],
            'vin' => ['required', 'string', 'min:5', 'max:17'],
            'vehicle_type' => ['required', 'in:M1,M1G,M2,M2G,M3,M3G,N1,N1G,N2,N2G,N3,N3G,O1,O2,O3,O4,L1e,L2e,L3e,L4e,L5e,L6e,L7e,T,C,R,S'],
            'brand' => ['required', 'string'],
            'model' => ['required', 'string'],
            'year_of_construction' => ['required', 'integer'],
            'engine_displacement' => ['required', 'integer'],
            'engine_power' => ['required', 'integer'],
            'total_weight' => ['required', 'integer'],
            'seats' => ['required', 'integer'],
            'fuel_type' => ['required', 'in:diesel,petrol,electric,hybrid,lpg'],
            'first_registration' => ['required', 'date'],
            'usage_type' => ['required', 'in:personal,passengerTransportation,taxi,carRental,drivingSchool,security,courier,cargoTransportation,distribution'],
            'vehicle_identification' => ['required', 'string'],
            'current_mileage' => ['required', 'integer'],
            'expiration_date_pti' => ['nullable', 'date'],
        ]);

        $apiToken = session('rca_api_token');

        if (!$apiToken) {
            return redirect()->route('rca.login');
        }

        $person = $this->buildPerson($validated);

        RcaCustomer::updateOrCreate(
            ['tax_id' => $validated['tax_id']],
            [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'mobile_number' => $validated['mobile_number'],
                'payload' => $validated,
            ]
        );

        $allOffers = [];
        $allResponses = [];

        foreach (config('rca_insurers') as $providerKey => $insurerConfig) {
            if (($insurerConfig['requires_pti'] ?? false) && empty($validated['expiration_date_pti'])) {
                $allResponses[$providerKey] = [
                    'status' => 'skipped',
                    'response' => [
                        'message' => 'Lipsă expirationDatePti pentru acest asigurator.',
                    ],
                ];

                continue;
            }

            $payload = $this->buildOfferPayload(
                $validated,
                $person,
                $providerKey,
                $insurerConfig
            );

            try {
                $response = Http::timeout(60)
                    ->connectTimeout(15)
                    ->withHeaders([
                        'Token' => $apiToken,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Content-Language' => 'ro',
                    ])
                    ->post(config('rca.base_url') . '/offer', $payload);

                $result = $response->json();
            } catch (\Throwable $e) {
                $result = [
                    'error' => true,
                    'message' => $e->getMessage(),
                ];

                $allResponses[$providerKey] = [
                    'status' => 'exception',
                    'response' => $result,
                ];

                RcaOfferLog::create([
                    'request_payload' => $payload,
                    'response_payload' => $result,
                    'status' => 'error',
                    'error_message' => $e->getMessage(),
                ]);

                continue;
            }

            $allResponses[$providerKey] = [
                'status' => $response->status(),
                'response' => $result,
            ];

            RcaOfferLog::create([
                'request_payload' => $payload,
                'response_payload' => $result,
                'status' => $response->successful() ? 'success' : 'error',
                'error_message' => $response->successful() ? null : $response->body(),
            ]);

            if ($response->successful() && isset($result['data']['offers'])) {
                foreach ($result['data']['offers'] as $offer) {
                    $offer['provider_key'] = $providerKey;
                    $offer['provider_label'] = $insurerConfig['label'] ?? $providerKey;

                    $allOffers[] = $offer;
                }
            }
        }

        usort($allOffers, function ($a, $b) {
            return ($a['premiumAmount'] ?? PHP_INT_MAX) <=> ($b['premiumAmount'] ?? PHP_INT_MAX);
        });

        if (!empty($allOffers) && !empty($validated['email'])) {
            $this->sendBestOfferEmail($validated['email'], $allOffers[0], $apiToken);
        }

        return view('rca.offers-result', [
            'offers' => $allOffers,
            'responses' => $allResponses,
            'status' => 200,
        ]);
    }

    private function buildPerson(array $validated): array
    {
        return [
            'lastName' => $validated['last_name'],
            'firstName' => $validated['first_name'],
            'isForeignPerson' => false,
            'taxId' => $validated['tax_id'],
            'nationality' => 'RO',
            'citizenship' => 'RO',
            'gender' => $validated['gender'],
            'birthdate' => $validated['birthdate'],
            'email' => $validated['email'],
            'mobileNumber' => $validated['mobile_number'],
            'identification' => [
                'idType' => $validated['id_type'],
                'idNumber' => $validated['id_number'],
                'issueAuthority' => $validated['id_issue_authority'],
                'issueDate' => $validated['id_issue_date'],
            ],
            'drivingLicense' => [
                'issueDate' => $validated['driving_license_issue_date'],
            ],
            'address' => array_filter([
                'country' => $validated['country'],
                'county' => $validated['county'],
                'city' => $validated['city'],
                'cityCode' => (int) $validated['city_code'],
                'street' => $validated['street'],
                'houseNumber' => $validated['house_number'] ?? null,
                'building' => $validated['building'] ?? null,
                'staircase' => $validated['staircase'] ?? null,
                'apartment' => $validated['apartment'] ?? null,
                'floor' => $validated['floor'] ?? null,
                'postcode' => $validated['postcode'] ?? null,
            ], fn ($value) => $value !== null && $value !== ''),
            'hasDisability' => false,
            'isRetired' => false,
        ];
    }

    private function buildOfferPayload(
        array $validated,
        array $person,
        string $providerKey,
        array $insurerConfig
    ): array {
        $commissionLimit = (float) $validated['commission_percent_limit'];

        if (isset($insurerConfig['commission_percent_limit'])) {
            $commissionLimit = (float) $insurerConfig['commission_percent_limit'];
        }

        $payload = [
            'provider' => [
                'organization' => [
                    'businessName' => $providerKey,
                ],
                'authentication' => [
                    'account' => '',
                    'password' => '',
                    'code' => '',
                ],
            ],
            'product' => [
                'motor' => [
                    'startDate' => $validated['start_date'],
                    'termTime' => (int) $validated['term_time'],
                    'installmentCount' => (int) $validated['installment_count'],
                    'commissionPercentLimit' => $commissionLimit,
                    'generatePaymentLink' => (bool) ($insurerConfig['supports_payment_link'] ?? false),
                ],
                'policyholder' => $person,
                'vehicle' => [
                    'owner' => $person,
                    'driver' => [
                        [
                            'lastName' => $validated['last_name'],
                            'firstName' => $validated['first_name'],
                            'taxId' => $validated['tax_id'],
                            'identification' => [
                                'idNumber' => $validated['id_number'],
                            ],
                            'mobileNumber' => $validated['mobile_number'],
                        ],
                    ],
                    'licensePlate' => strtoupper($validated['license_plate']),
                    'registrationType' => $validated['registration_type'],
                    'vin' => strtoupper($validated['vin']),
                    'vehicleType' => $validated['vehicle_type'],
                    'brand' => $validated['brand'],
                    'model' => $validated['model'],
                    'yearOfConstruction' => (int) $validated['year_of_construction'],
                    'engineDisplacement' => (int) $validated['engine_displacement'],
                    'enginePower' => (int) $validated['engine_power'],
                    'totalWeight' => (int) $validated['total_weight'],
                    'seats' => (int) $validated['seats'],
                    'fuelType' => $validated['fuel_type'],
                    'firstRegistration' => $validated['first_registration'],
                    'usageType' => $validated['usage_type'],
                    'currentMileage' => (int) $validated['current_mileage'],
                    'hasMobilityModifications' => false,
                    'isLeased' => false,
                    'isNew' => false,
                    'identification' => [
                        'idNumber' => strtoupper($validated['vehicle_identification']),
                    ],
                ],
            ],
        ];

        if ($insurerConfig['requires_pti'] ?? false) {
            $payload['product']['additionalData'] = [
                'product' => [
                    'vehicle' => [
                        'expirationDatePti' => $validated['expiration_date_pti'],
                    ],
                ],
            ];
        }

        return $payload;
    }

    private function sendBestOfferEmail(string $email, array $offer, string $apiToken): void
    {
        $pdfContent = null;
        $fileName = null;

        if (!empty($offer['offerId'])) {
            try {
                $pdfResponse = Http::timeout(60)
                    ->connectTimeout(15)
                    ->withHeaders([
                        'Token' => $apiToken,
                        'Accept' => 'application/json',
                        'Content-Language' => 'ro',
                    ])
                    ->get(config('rca.base_url') . '/offer/' . $offer['offerId']);

                if ($pdfResponse->successful()) {
                    $pdfJson = $pdfResponse->json();
                    $file = $pdfJson['data']['files'][0] ?? null;

                    if ($file && !empty($file['content'])) {
                        $pdfContent = base64_decode($file['content']);
                        $fileName = $file['name'] ?? 'oferta-rca-' . $offer['offerId'] . '.pdf';
                    }
                }
            } catch (\Throwable $e) {
                $pdfContent = null;
                $fileName = null;
            }
        }

        Mail::to($email)->send(
            new RcaOfferMail($offer, $pdfContent, $fileName)
        );
    }

    public function downloadOfferPdf($offerId)
    {
        set_time_limit(120);

        $token = session('rca_api_token');

        if (!$token) {
            return redirect()
                ->route('rca.login')
                ->withErrors(['token' => 'Tokenul API lipsește. Obține din nou tokenul.']);
        }

        $response = Http::timeout(60)
            ->connectTimeout(15)
            ->withHeaders([
                'Token' => $token,
                'Accept' => 'application/json',
                'Content-Language' => 'ro',
            ])
            ->get(config('rca.base_url') . '/offer/' . $offerId);

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Nu s-a putut descărca oferta.',
                'status' => $response->status(),
                'response' => $response->json() ?? $response->body(),
            ], $response->status());
        }

        return $this->downloadPdfFromJsonResponse($response->json(), 'oferta-rca-' . $offerId . '.pdf');
    }

    public function downloadPolicyPdf($policyId)
    {
        set_time_limit(120);

        $token = session('rca_api_token');

        if (!$token) {
            return redirect()
                ->route('rca.login')
                ->withErrors(['token' => 'Tokenul API lipsește. Obține din nou tokenul.']);
        }

        $response = Http::timeout(60)
            ->connectTimeout(15)
            ->withHeaders([
                'Token' => $token,
                'Accept' => 'application/json',
                'Content-Language' => 'ro',
            ])
            ->get(config('rca.base_url') . '/policy/' . $policyId);

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Nu s-a putut descărca polița.',
                'status' => $response->status(),
                'response' => $response->json() ?? $response->body(),
            ], $response->status());
        }

        return $this->downloadPdfFromJsonResponse($response->json(), 'polita-rca-' . $policyId . '.pdf');
    }

    private function downloadPdfFromJsonResponse(?array $json, string $fallbackFileName)
    {
        $file = $json['data']['files'][0] ?? null;

        if (!$file || empty($file['content'])) {
            return response()->json([
                'message' => 'Răspunsul nu conține fișier PDF.',
                'response' => $json,
            ], 400);
        }

        $pdfContent = base64_decode($file['content'], true);

        if ($pdfContent === false) {
            return response()->json([
                'message' => 'Conținutul PDF nu este base64 valid.',
            ], 400);
        }

        $fileName = $file['name'] ?? $fallbackFileName;

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }

    public function transformToPolicy(Request $request, int $offerId)
    {
        set_time_limit(120);

        $validated = $request->validate([
            'payment_method' => ['required', 'in:receipt,broker receipt,payment order,broker payment order,pos'],
            'premium_amount' => ['required', 'numeric', 'min:0'],
            'include_direct_compensation' => ['required', 'boolean'],
        ]);

        $token = session('rca_api_token');

        if (!$token) {
            return redirect()
                ->route('rca.login')
                ->withErrors(['token' => 'Tokenul API lipsește. Obține din nou tokenul.']);
        }

        $payload = [
            'offerId' => $offerId,
            'includeDirectCompensation' => (bool) $validated['include_direct_compensation'],
            'payment' => [
                'method' => $validated['payment_method'],
                'currency' => 'RON',
                'amount' => (float) $validated['premium_amount'],
                'date' => now()->format('Y-m-d'),
                'documentNumber' => 'DOC-' . $offerId,
            ],
        ];

        $response = Http::timeout(60)
            ->connectTimeout(15)
            ->withHeaders([
                'Token' => $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Content-Language' => 'ro',
            ])
            ->post(config('rca.base_url') . '/policy', $payload);

        $result = $response->json();

        RcaPolicyLog::create([
            'offer_id' => $offerId,
            'policy_id' => data_get($result, 'data.policies.0.policyId'),
            'request_payload' => $payload,
            'response_payload' => $result,
            'status' => $response->successful() ? 'success' : 'error',
            'error_message' => $response->successful() ? null : $response->body(),
        ]);

        return view('rca.policy-result', [
            'result' => $result,
            'status' => $response->status(),
            'offerId' => $offerId,
        ]);
    }
}