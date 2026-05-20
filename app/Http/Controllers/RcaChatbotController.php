<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\RcaOfferService;
use Illuminate\Support\Facades\Mail;

class RcaChatbotController extends Controller
{
    protected RcaOfferService $offerService;

    public function __construct(RcaOfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    public function index()
    {
        return view('rca.chatbot');
    }

    public function createOffer(Request $request)
    {
        $validated = [
            'start_date' => $request->startDate,
            'term_time' => (int) $request->termTime,
            'installment_count' => (int) $request->installmentCount,
            'commission_percent_limit' => (float) $request->commissionPercentLimit,

            'last_name' => $request->lastName,
            'first_name' => $request->firstName,
            'tax_id' => $request->personalCode,
            'birthdate' => $request->birthdate,
            'gender' => strtolower($request->gender),
            'email' => $request->email,
            'mobile_number' => $request->phone,

            'id_type' => strtoupper($request->idType),
            'id_number' => $request->idNumber,
            'id_issue_authority' => $request->idIssueAuthority,
            'id_issue_date' => $request->idIssueDate,
            'driving_license_issue_date' => $request->drivingLicenseIssueDate,

            'country' => strtoupper($request->country),
            'county' => strtoupper($request->county),
            'city' => $request->city,
            'city_code' => (int) $request->cityCode,
            'street' => $request->street,
            'house_number' => $request->houseNumber,
            'building' => null,
            'staircase' => null,
            'apartment' => null,
            'floor' => null,
            'postcode' => $request->postcode,

            'license_plate' => strtoupper($request->licensePlate),
            'registration_type' => $request->registrationType,
            'vin' => strtoupper($request->vin),
            'vehicle_type' => strtoupper($request->vehicleType),
            'brand' => $request->brand,
            'model' => $request->model,
            'year_of_construction' => (int) $request->yearOfConstruction,
            'engine_displacement' => (int) $request->engineDisplacement,
            'engine_power' => (int) $request->enginePower,
            'total_weight' => (int) $request->totalWeight,
            'seats' => (int) $request->seats,
            'fuel_type' => strtolower($request->fuelType),
            'first_registration' => $request->firstRegistration,
            'usage_type' => $request->usageType,
            'vehicle_identification' => strtoupper($request->vehicleIdentification),
            'current_mileage' => (int) $request->currentMileage,
            'expiration_date_pti' => $request->expirationDatePti,
        ];

        $apiToken = session('rca_api_token');

        if (!$apiToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token-ul RCA lipsește. Te rugăm să te autentifici din nou.'
            ], 401);
        }

        $result = $this->offerService->generateOffers($validated, $apiToken);

        return response()->json([
            'success' => true,
            'offers' => $result['offers'],
            'debug_response' => $result['responses'],
        ]);
    }

   public function generatePolicy(Request $request)
{
    $offerId = $request->offerId;
    $email = $request->email;

    if (!$offerId) {
        return response()->json([
            'success' => false,
            'message' => 'Offer ID lipsă.'
        ], 400);
    }

    $apiToken = session('rca_api_token');

    if (!$apiToken) {
        return response()->json([
            'success' => false,
            'message' => 'Token RCA expirat.'
        ], 401);
    }

    try {
        $response = Http::withHeaders([
            'Token' => $apiToken,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Content-Language' => 'ro',
        ])->post(config('rca.base_url') . '/policy', [
            'offerId' => $offerId,
            'payment' => [
                'amount' => (float) $request->amount,
                'method' => 'pos',
                'currency' => 'RON',
                'documentNumber' => 'CHATBOT-' . $offerId,
                'date' => now()->format('Y-m-d'),
            ],
        ]);

        $json = $response->json();

        if (!$response->successful() || ($json['error'] ?? false) === true) {
            return response()->json([
                'success' => false,
                'message' => $json['message'] ?? 'Eroare la generarea poliței.',
                'errors' => $json['data'] ?? null,
            ], $response->status());
        }

        $policyId =
        $json['data']['policyId'] ??
        $json['policyId'] ??
        $json['data']['policies'][0]['policyId'] ??
        null;

        if (!$policyId) {
            return response()->json([
                'success' => false,
                'message' => 'Polița a fost generată, dar nu am primit policyId.',
                'data' => $json,
            ], 500);
        }

        $pdfResponse = Http::timeout(60)
            ->connectTimeout(15)
            ->withHeaders([
                'Token' => $apiToken,
                'Accept' => 'application/json',
                'Content-Language' => 'ro',
            ])
            ->get(config('rca.base_url') . '/policy/' . $policyId);

        $pdfJson = $pdfResponse->json();
        $file = $pdfJson['data']['files'][0] ?? null;

        if ($file && !empty($file['content']) && !empty($email)) {
            $pdfContent = base64_decode($file['content']);
            $fileName = $file['name'] ?? 'polita-rca-' . $policyId . '.pdf';

            Mail::raw('Atașat găsiți polița RCA generată prin chatbot.', function ($message) use ($email, $pdfContent, $fileName) {
                $message->to($email)
                    ->subject('Polița RCA generată')
                    ->attachData($pdfContent, $fileName, [
                        'mime' => 'application/pdf',
                    ]);
            });
        }

        return response()->json([
            'success' => true,
            'policyId' => $policyId,
            'emailSent' => !empty($file) && !empty($email),
            'data' => $json,
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
}