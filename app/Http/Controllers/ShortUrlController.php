<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\HashGenerator;

class ShortUrlController extends Controller
{


    protected $hashGenerator;

    public function __construct(HashGenerator $hashGenerator)
    {
        $this->hashGenerator = $hashGenerator;
    }

    // ...

    /**
     * Generate a unique hash for a URL.
     *
     * @return string
     */
    protected function generateHash()
    {
        return $this->hashGenerator->generateHash();
    }


    /**
     * Create and Generate a short URL for the provided original URL.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createShortUrl(Request $request)
    {

        // Validate the request
        $request->validate([
            'original_url' => 'required|url',
        ]);

        // Check if the URL already exists
        $existingUrl = ShortUrl::where('original_url', $request->input('original_url'))->first();

        if ($existingUrl) {
            $shortened_url = config('app.url') . '/' . $existingUrl->hash;
            // If the URL already exists, return the existing short URL
            return response()->json(['shortened_url' => $shortened_url]);
        }

        // Generate a unique hash
        $hash = $this->generateHash();

        // Create a new short URL record
        $shortUrl = ShortUrl::create([
            'original_url' => $request->input('original_url'),
            'hash' => $hash,
        ]);
        $shortened_url = config('app.url') . '/' . $hash;
        return response()->json(['shortened_url' => $shortened_url]);
    }

    /**
     * Redirect to the original URL based on the short URL hash.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function redirectToOriginal($hash)
    {
        // ... your code for redirecting to the original URL ...
    }
}
