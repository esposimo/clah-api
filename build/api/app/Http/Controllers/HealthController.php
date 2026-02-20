<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\PlatformStateService;
use Illuminate\Http\JsonResponse;

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
