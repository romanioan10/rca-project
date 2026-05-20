<?php

namespace App\Services;

use App\Models\RcaCustomer;
use App\Models\RcaOfferLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RcaOfferService
{
    public function generateOffers(array $validated, string $apiToken): array
    {
        set_time_limit(180);

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

                $response = Http::timeout(120)
                    ->connectTimeout(15)
                    ->withHeaders([
                        'Token' => $apiToken,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Content-Language' => 'ro',
                    ])
                    ->post(config('rca.base_url') . '/offer', $payload);

                $json = $response->json();

            } catch (\Throwable $e) {

                $json = [
                    'error' => true,
                    'message' => $e->getMessage(),
                ];

                $allResponses[$providerKey] = [
                    'status' => 'exception',
                    'response' => $json,
                ];

                continue;
            }

            if ($response->successful()) {

                Log::info('FULL PROVIDER RESPONSE', [
                    'provider' => $providerKey,
                    'response' => $json,
                ]);

                $offers = [];

                if (isset($json['data']['offers'])) {
                    $offers = $json['data']['offers'];
                } elseif (isset($json['offers'])) {
                    $offers = $json['offers'];
                } elseif (isset($json['data']) && is_array($json['data'])) {
                    $offers = $json['data'];
                }

                if (!empty($offers)) {

                    foreach ($offers as $offer) {

                        if (!is_array($offer)) {
                            continue;
                        }

                        $offer['providerKey'] = $providerKey;

                        $allOffers[] = $offer;
                    }

                } else {

                    $allResponses[$providerKey] = [
                        'status' => $response->status(),
                        'response' => $json,
                    ];
                }

            } else {

                $allResponses[$providerKey] = [
                    'status' => $response->status(),
                    'response' => $json,
                ];

                continue;
            }

            RcaOfferLog::create([
                'request_payload' => $payload,
                'response_payload' => $json,
                'status' => $response->successful() ? 'success' : 'error',
                'error_message' => $response->successful() ? null : json_encode($json),
            ]);
        }

        usort($allOffers, function ($a, $b) {
            return ($a['premiumAmount'] ?? PHP_INT_MAX)
                <=>
                ($b['premiumAmount'] ?? PHP_INT_MAX);
        });

        return [
            'offers' => $allOffers,
            'responses' => $allResponses,
        ];
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
            ], fn($value) => $value !== null && $value !== ''),

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

                    'identification' => [
                        'idNumber' => $validated['vehicle_identification'],
                    ],

                    'vin' => strtoupper($validated['vin']),
                    'vehicleType' => strtoupper($validated['vehicle_type']),
                    'brand' => $validated['brand'],
                    'model' => $validated['model'],

                    'yearOfConstruction' => (int) $validated['year_of_construction'],
                    'engineDisplacement' => (int) $validated['engine_displacement'],
                    'enginePower' => (int) $validated['engine_power'],
                    'totalWeight' => (int) $validated['total_weight'],
                    'seats' => (int) $validated['seats'],

                    'fuelType' => strtolower($validated['fuel_type']),
                    'firstRegistration' => $validated['first_registration'],
                    'usageType' => $validated['usage_type'],
                    'currentMileage' => (int) $validated['current_mileage'],

                    'hasMobilityModifications' => false,
                    'isLeased' => false,
                    'isNew' => false,
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
}