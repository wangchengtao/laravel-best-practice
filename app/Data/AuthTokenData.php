<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

class AuthTokenData extends Data
{
    public function __construct(
        public string $access_token,
        public string $token_type,
        public int $expires_in,
    ) {
    }
}
