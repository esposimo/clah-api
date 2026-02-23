<?php

declare(strict_types=1);

namespace src\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use src\app\Services\PlatformStateService;
use function app\Http\Controllers\response;

final class HealthController extends Controller
{
    public function __construct(private readonly PlatformStateService $platformStateService)
    {
    }

    public function __invoke(): JsonResponse
    {
        return response()->json($this->platformStateService->health());
    }
}
