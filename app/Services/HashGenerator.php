<?php

namespace App\Services;

use Illuminate\Support\Str;

class HashGenerator
{
    /**
     * Generate a unique hash.
     *
     * @param int $length
     * @return string
     */
    public function generateHash()
    {
        $length = config('hash.length', 6); // Retrieve the hash length from your configuration. Default to 6 if not set.
        return Str::random($length);
    }
}
