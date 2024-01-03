<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;


class SafeBrowsingService
{
    public function isSafe($url)
    {
        $apiKey = config('services.safe_browsing_api_key');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post("https://safebrowsing.googleapis.com/v4/threatMatches:find?key=$apiKey", [
            'client' => [
                'clientId' => 'coding_test',
                'clientVersion' => '1.5.2'
            ],
            'threatInfo' => [
                'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING'],
                'platformTypes' => ['WINDOWS'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [
                    ['url' => $url]
                ]
            ]
        ]);

        if ($response->successful()) {
            if (count($response->json()) === 0) {
                return true;
            }
        }

        return false;
    }
}
