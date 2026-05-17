<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RcaAuthService
{
    public function getToken(): string
    {
        return Cache::remember('rca_auth_token', now()->addMinutes(50), function () {
            $response = Http::post(config('rca.base_url') . '/auth', [
                'account' => config('rca.account'),
                'password' => config('rca.password'),
            ]);

            if (! $response->successful()) {
                throw new \Exception('Autentificarea RCA a eșuat: ' . $response->body());
            }

            $json = $response->json();

            if (empty($json['data']['token'])) {
                throw new \Exception('Tokenul RCA nu a fost găsit în răspuns.');
            }

            return $json['data']['token'];
        });
    }
}