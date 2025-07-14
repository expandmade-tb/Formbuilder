<?php

/**
 * Stateless CSRF Protection Class
 * Version 2.1 - Secure & Improved
 * Author: expandmade / TB
 */

namespace Formbuilder;

class StatelessCSRF {
    private const HASH_ALGO = 'sha256';
    private string $key;
    private array $data = [];

    public function __construct(string $secret_key) {
        $this->key = $secret_key;
    }

    /**
     * Set user-specific data to bind the CSRF token to context (e.g., IP, User-Agent).
     */
    public function setGlueData(string $key, string $value): void {
        $this->data[$key] = $value;
    }

    public function resetGlue(): void {
        $this->data = [];
    }

    /**
     * Generate a stateless CSRF token.
     */
    public function getToken(string $identifier, ?int $expiration = null): string {
        $seed = $this->getRandomSeed();
        $hash = $this->generateHash($identifier, $seed, $expiration, $this->data);

        $token = implode('|', [$seed, (string)($expiration ?? ''), $hash]);
        return $this->urlSafeBase64Encode($token);
    }

    /**
     * Validate a provided CSRF token.
     */
    public function validate(string $identifier, string $provided_token, ?int $current_time = null): bool {
        $decoded = $this->urlSafeBase64Decode($provided_token);

        if (!$decoded) {
            return false;
        }

        $parts = explode('|', $decoded, 3);

        if (count($parts) !== 3) {
            return false;
        }

        [$seed, $expirationRaw, $provided_hash] = $parts;

        if ($expirationRaw === '') {
            $expiration = null;
        } elseif (!ctype_digit($expirationRaw)) {
            return false;
        } else {
            $expiration = (int)$expirationRaw;
            $current_time ??= time();
            if ($current_time > $expiration) {
                return false;
            }
        }

        $expected_hash = $this->generateHash($identifier, $seed, $expiration, $this->data);
        return hash_equals($expected_hash, $provided_hash);
    }

    private function generateHash(string $identifier, string $random_seed, ?int $expiration = null, array $data = []): string {
        $encodedIdentifier = $this->urlSafeBase64Encode($identifier);
        $payload = implode('|', [
            $encodedIdentifier,
            (string)($expiration ?? ''),
            json_encode($data, JSON_THROW_ON_ERROR),
            $random_seed
        ]);

        return $this->urlSafeBase64Encode(hash_hmac(self::HASH_ALGO, $payload, $this->key, true));
    }

    private function getRandomSeed(): string {
        return $this->urlSafeBase64Encode(random_bytes(16)); // 128-bit
    }

    private function urlSafeBase64Encode(string $input): string {
        return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
    }

    private function urlSafeBase64Decode(string $input): string|false {
        $padded = str_pad($input, strlen($input) % 4 === 0 ? strlen($input) : strlen($input) + 4 - strlen($input) % 4, '=');
        return base64_decode(strtr($padded, '-_', '+/'), true);
    }

    public function __debugInfo(): array {
        return [
            'data' => $this->data,
        ];
    }
}