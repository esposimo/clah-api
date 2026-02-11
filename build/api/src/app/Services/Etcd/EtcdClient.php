<?php

declare(strict_types=1);

namespace App\Services\Etcd;

use RuntimeException;

/**
 * Minimal etcd v3 JSON gateway client.
 */
class EtcdClient
{
    public function __construct(private readonly string $baseUri = 'http://clah-kv-store:2379')
    {
    }

    /**
     * @param array<string, mixed> $value
     */
    public function put(string $key, array $value): void
    {
        $this->request('/v3/kv/put', [
            'key' => base64_encode($key),
            'value' => base64_encode((string) json_encode($value, JSON_THROW_ON_ERROR)),
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function get(string $key): ?array
    {
        $response = $this->request('/v3/kv/range', [
            'key' => base64_encode($key),
        ]);

        if (!isset($response['kvs'][0]['value']) || !is_string($response['kvs'][0]['value'])) {
            return null;
        }

        $decoded = base64_decode($response['kvs'][0]['value'], true);
        if ($decoded === false) {
            throw new RuntimeException('Invalid base64 payload from etcd.');
        }

        $payload = json_decode($decoded, true, 512, JSON_THROW_ON_ERROR);

        return is_array($payload) ? $payload : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getByPrefix(string $prefix): array
    {
        $response = $this->request('/v3/kv/range', [
            'key' => base64_encode($prefix),
            'range_end' => base64_encode($this->nextPrefix($prefix)),
        ]);

        $rows = [];
        foreach ($response['kvs'] ?? [] as $kv) {
            if (!isset($kv['value']) || !is_string($kv['value'])) {
                continue;
            }

            $decoded = base64_decode($kv['value'], true);
            if ($decoded === false) {
                continue;
            }

            $payload = json_decode($decoded, true);
            if (is_array($payload)) {
                $rows[] = $payload;
            }
        }

        return $rows;
    }

    public function delete(string $key): void
    {
        $this->request('/v3/kv/deleterange', [
            'key' => base64_encode($key),
        ]);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function request(string $path, array $payload): array
    {
        $url = sprintf('%s%s', rtrim($this->baseUri, '/'), $path);

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content' => (string) json_encode($payload, JSON_THROW_ON_ERROR),
                'ignore_errors' => true,
                'timeout' => 5,
            ],
        ]);

        $result = file_get_contents($url, false, $context);
        if ($result === false) {
            throw new RuntimeException(sprintf('Failed etcd request to %s', $url));
        }

        $decoded = json_decode($result, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('Invalid etcd response payload.');
        }

        return $decoded;
    }

    private function nextPrefix(string $prefix): string
    {
        if ($prefix === '') {
            return "\0";
        }

        $chars = str_split($prefix);
        for ($index = count($chars) - 1; $index >= 0; $index--) {
            $ord = ord($chars[$index]);
            if ($ord < 255) {
                $chars[$index] = chr($ord + 1);

                return implode('', array_slice($chars, 0, $index + 1));
            }
        }

        return $prefix . "\0";
    }
}
