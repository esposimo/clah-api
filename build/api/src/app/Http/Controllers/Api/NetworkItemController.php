<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Network\Network;
use App\Domain\Network\NetworkItem;
use App\Http\Controllers\Controller;
use App\Services\Network\NetworkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

/**
 * Manage network items scoped by environment.
 */
class NetworkItemController extends Controller
{
    public function __construct(private readonly NetworkService $networkService)
    {
    }

    /**
     * List network items for a specific environment.
     */
    public function index(string $envId): JsonResponse
    {
        $items = new NetworkItem($envId);

        foreach ($this->networkService->allByEnvironment($envId) as $payload) {
            $id = (string) ($payload['id'] ?? '');
            if ($id !== '') {
                $items->add(new Network($id, $envId));
            }
        }

        return response()->json([
            'message' => 'Implement network items list logic.',
            'env_id' => $envId,
            'data' => $items->toArray(),
        ], 200);
    }

    /**
     * Create a network item for a specific environment.
     */
    public function store(Request $request, string $envId): JsonResponse
    {
        $network = new Network();
        $network->setEnvironmentId($envId);
        $network->setName((string) $request->input('name', ''));
        $network->setType((string) $request->input('type', 'bridge'));
        $network->setSubnet((string) $request->input('subnet', ''));
        $network->setGateway((string) $request->input('gateway', ''));
        $network->setProvider((bool) $request->input('provider', false));
        $network->setAttributes((array) $request->input('attributes', []));

        try {
            $id = $network->save();
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Implement network item create logic.',
            'id' => $id,
            'item' => $network->toArray(),
        ], 200);
    }

    /**
     * Get one network item for a specific environment.
     */
    public function show(string $envId, string $id): JsonResponse
    {
        $network = new Network($id, $envId);

        return response()->json([
            'message' => 'Implement network item read logic.',
            'env_id' => $envId,
            'item' => $network->toArray(),
        ], 200);
    }

    /**
     * Update one network item for a specific environment.
     */
    public function update(Request $request, string $envId, string $id): JsonResponse
    {
        $network = new Network($id, $envId);
        $network->setEnvironmentId($envId);
        $network->setName((string) $request->input('name', $network->name()));
        $network->setType((string) $request->input('type', $network->type()));
        $network->setSubnet((string) $request->input('subnet', $network->subnet()));
        $network->setGateway((string) $request->input('gateway', $network->gateway()));
        $network->setProvider((bool) $request->input('provider', $network->isProvider()));
        $network->setAttributes((array) $request->input('attributes', $network->attributes()));

        try {
            $network->save();
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Implement network item update logic.',
            'env_id' => $envId,
            'id' => $id,
            'item' => $network->toArray(),
        ], 200);
    }

    /**
     * Delete one network item for a specific environment.
     */
    public function destroy(string $envId, string $id): JsonResponse
    {
        $network = new Network($id, $envId);
        $network->delete();

        return response()->json([
            'message' => 'Implement network item delete logic.',
            'env_id' => $envId,
            'id' => $id,
        ], 200);
    }
}
