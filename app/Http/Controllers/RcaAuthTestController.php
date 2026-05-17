<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RcaAuthTestController extends Controller
{
    public function index()
    {
        return view('rca.auth-test');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'account' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $response = Http::post(config('rca.base_url') . '/auth', [
            'account' => $request->account,
            'password' => $request->password,
        ]);

        $json = $response->json();

        return view('rca.auth-test', [
            'response' => $json,
            'token' => $json['data']['token'] ?? null,
            'status' => $response->status(),
            'successful' => $response->successful(),
        ]);
    }
}