<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Kirim pesan WhatsApp via Fonnte.
     */
    public function kirim(string $nomor, string $pesan): array
    {
        $token  = config('services.fonnte.token');
        $target = $this->formatNomor($nomor);

        Log::info('Fonnte BEFORE SEND', [
            'target'       => $target,
            'token_length' => strlen($token),
            'token_prefix' => substr($token, 0, 8),
        ]);

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->post('https://api.fonnte.com/send', [
            'target'  => $target,
            'message' => $pesan,
        ]);

        Log::info('Fonnte RESPONSE', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        if (!$response->successful()) {
            Log::error('Gagal kirim WhatsApp via Fonnte', [
                'nomor'    => $target,
                'response' => $response->body(),
            ]);
        }

        return $response->json() ?? [];
    }

    /**
     * Konversi 08xx -> 628xx
     */
    private function formatNomor(string $nomor): string
    {
        $nomor = preg_replace('/\s+/', '', $nomor);

        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        return $nomor;
    }
}