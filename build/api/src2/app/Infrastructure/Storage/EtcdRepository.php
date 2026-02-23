<?php

declare(strict_types=1);

namespace src\app\Infrastructure\Storage;

use Illuminate\Support\Facades\Http;
use src\app\Domain\RepositoryInterface;

final class EtcdRepository implements RepositoryInterface
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) env('ETCD_ENDPOINT', 'http://kv-store:2379'), '/');
    }

    public function get(string $path): mixed
    {
        $response = Http::post($this->endpoint('/v3/kv/range'), [
            'key' => $this->encode($this->normalizePath($path)),
        ]);

        if (! $response->successful()) {
            return null;
        }

        $kvs = $response->json('kvs');

        if (! is_array($kvs) || $kvs === []) {
            return null;
        }

        $value = $kvs[0]['value'] ?? null;

        return is_string($value) ? base64_decode($value, true) : null;
    }

    public function put(string $path, string $value): bool
    {
        $response = Http::post($this->endpoint('/v3/kv/put'), [
            'key' => $this->encode($this->normalizePath($path)),
            'value' => $this->encode($value),
        ]);

        return $response->successful();
    }

    public function del(string $path): bool
    {
        $response = Http::post($this->endpoint('/v3/kv/deleterange'), [
            'key' => $this->encode($this->normalizePath($path)),
        ]);

        return $response->successful();
    }

    public function list(string $path): ?array
    {
        $normalizedPath = $this->normalizePath($path);
        $prefix = rtrim($normalizedPath, '/').'/';

        $response = Http::post($this->endpoint('/v3/kv/range'), [
            'key' => $this->encode($prefix),
            'range_end' => $this->prefixRangeEnd($prefix),
            'keys_only' => true,
        ]);

        if (! $response->successful()) {
            return null;
        }

        $kvs = $response->json('kvs');

        if (! is_array($kvs)) {
            return null;
        }

        $keys = [];

        foreach ($kvs as $item) {
            $rawKey = $item['key'] ?? null;

            if (! is_string($rawKey)) {
                continue;
            }

            $decodedKey = base64_decode($rawKey, true);

            if (! is_string($decodedKey)) {
                continue;
            }

            $keys[] = $decodedKey;
        }

        return $keys;
    }

    private function endpoint(string $path): string
    {
        return $this->baseUrl.$path;
    }

    private function normalizePath(string $path): string
    {
        return '/'.ltrim($path, '/');
    }

    private function encode(string $value): string
    {
        return base64_encode($value);
    }

    private function prefixRangeEnd(string $prefix): string
    {
        $lastChar = substr($prefix, -1);

        if ($lastChar === false || $lastChar === '') {
            return $this->encode("\0");
        }

        $prefixStart = substr($prefix, 0, -1);
        $nextByte = chr(ord($lastChar) + 1);

        return $this->encode($prefixStart.$nextByte);
    }
}
