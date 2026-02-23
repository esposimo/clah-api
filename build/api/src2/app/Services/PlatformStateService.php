<?php

declare(strict_types=1);

namespace src\app\Services;

final class PlatformStateService
{
    public function health(): array
    {
        return [
            'status' => 'ok',
            'component' => 'api-control-plane',
        ];
    }
}
