<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;

use Illuminate\Http\Request;
use App\Services\HashGenerator;
use App\Services\SafeBrowsingService;

class ShortUrlController extends Controller
{


    protected $hashGenerator;
    protected $safeBrowsingService;

    public function __construct(HashGenerator $hashGenerator, SafeBrowsingService $safeBrowsingService)
    {
        $this->hashGenerator = $hashGenerator;
        $this->safeBrowsingService = $safeBrowsingService;
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

        $original_url = $request->input('original_url');

        if (!$this->safeBrowsingService->isSafe($original_url)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // Check if the URL already exists
        $existingUrl = ShortUrl::where('original_url', $original_url)->first();

        if ($existingUrl) {
            $shortened_url = config('app.url') . '/' . $existingUrl->hash;
            // If the URL already exists, return the existing short URL
            return response()->json(['shortened_url' => $shortened_url]);
        }

        // Generate a unique hash
        $hash = $this->generateHash();

        // Create a new short URL record
        $shortUrl = ShortUrl::create([
            'original_url' => $original_url,
            'hash' => $hash,
        ]);
        // $shortened_url = config('app.url') . '/' . $hash;
        return response()->json(['shortened_url' => $hash], 201);
    }

    /**
     * Redirect to the original URL based on the short URL hash.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function redirectToOriginal($hash)
    {
        // Find the ShortUrl record by hash
        $shortUrl = ShortUrl::where('hash', $hash)->first();

        if (!$shortUrl) {
            // If no matching short URL is found, you can handle this situation (e.g., show a 404 page)
            abort(404, 'Short URL not found');
        }

        // Redirect to the original URL
        return redirect()->away($shortUrl->original_url);
    }

    /**
     * return a listing of the URLs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $urls = ShortUrl::all(); // Get all URLs from the Url model

        return response()->json($urls); // Return the URLs as a JSON response
    }
}
