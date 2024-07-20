<?php

namespace App\Services;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

class JwtService
{
    private $config;

    public function __construct()
    {
        $this->config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::base64Encoded(config('app.jwt_secret'))
        );
    }

    public function createToken(array $claims)
    {
        $now = new \DateTimeImmutable();

        $token = $this->config->builder()
            ->issuedBy(config('app.url'))
            ->permittedFor(config('app.url'))
            ->issuedAt($now)
            ->expiresAt($now->modify('+1 hour'));

        // Add custom claims
        foreach ($claims as $key => $value) {
            $token = $token->withClaim($key, $value);
        }

        return $token->getToken($this->config->signer(), $this->config->signingKey());
    }

    public function parseToken(string $token)
    {
        return $this->config->parser()->parse($token);
    }

    public function validateToken($token)
    {
        $constraint = new SignedWith($this->config->signer(), $this->config->signingKey());
        
        try {
            return $this->config->validator()->validate($token, $constraint);
        } catch (\Exception $e) {
            /* \Log::error('JWT Validation failed: ' . $e->getMessage()); */
            return false;
        }
    }

}
