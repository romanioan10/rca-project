<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\RcaCustomer;

class RcaSimpleAuthController extends Controller
{
    public function showLogin()
    {
        return view('rca.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'account' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $response = Http::post(config('rca.base_url') . '/auth', [
            'account' => $validated['account'],
            'password' => $validated['password'],
        ]);

        if (!$response->successful()) {
            return back()->withInput()->withErrors([
                'login' => 'Autentificarea RCA a eșuat.',
            ]);
        }

        $json = $response->json();
        $token = $json['data']['token'] ?? null;

        if (!$token) {
            return back()->withInput()->withErrors([
                'login' => 'Tokenul nu a fost găsit în răspuns.',
            ]);
        }

        session([
            'rca_api_token' => $token,
            'rca_logged_in' => true,
            'rca_account' => $validated['account'],
        ]);

        RcaCustomer::updateOrCreate(
            ['tax_id' => 'API_USER_' . $validated['account']],
            [
                'first_name' => 'RCA',
                'last_name' => 'User',
                'email' => null,
                'mobile_number' => null,
                'payload' => [
                    'account' => $validated['account'],
                    'logged_in_at' => now()->toDateTimeString(),
                ],
            ]
        );

        return redirect()->route('rca.dashboard');
    }

    public function logout()
    {
        session()->forget([
            'rca_api_token',
            'rca_logged_in',
            'rca_account',
        ]);

        return redirect()->route('rca.login');
    }
}