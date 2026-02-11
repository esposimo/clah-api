<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Environment\Environment;
use App\Domain\Environment\EnvironmentItem;
use App\Http\Controllers\Controller;
use App\Services\Environment\EnvironmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Manage environment-scoped items.
 */
class EnvItemController extends Controller
{
    public function __construct(private readonly EnvironmentService $environmentService)
    {
    }

    /**
     * List all environment items.
     */
    public function index(): JsonResponse
    {
        $items = new EnvironmentItem();
        foreach ($this->environmentService->all() as $payload) {
            $id = (string) ($payload['id'] ?? '');
            if ($id !== '') {
                $items->add(new Environment($id));
            }
        }

        return response()->json([
            'message' => 'Implement env items list logic.',
            'data' => $items->toArray(),
        ], 200);
    }

    /**
     * Create a new environment item.
     */
    public function store(Request $request): JsonResponse
    {
        $environment = new Environment();
        $environment->setName((string) $request->input('name', ''));
        $environment->setDescription($request->input('description') !== null ? (string) $request->input('description') : null);
        $environment->setAttributes((array) $request->input('attributes', []));
        $id = $environment->save();

        return response()->json([
            'message' => 'Implement env item create logic.',
            'id' => $id,
            'item' => $environment->toArray(),
        ], 200);
    }

    /**
     * Get a single environment item.
     */
    public function show(string $id): JsonResponse
    {
        $environment = new Environment($id);

        return response()->json([
            'message' => 'Implement env item read logic.',
            'item' => $environment->toArray(),
        ], 200);
    }

    /**
     * Update an existing environment item.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $environment = new Environment($id);
        $environment->setName((string) $request->input('name', $environment->name()));
        $environment->setDescription($request->input('description', $environment->description()));
        $environment->setAttributes((array) $request->input('attributes', $environment->attributes()));
        $environment->save();

        return response()->json([
            'message' => 'Implement env item update logic.',
            'id' => $id,
            'item' => $environment->toArray(),
        ], 200);
    }

    /**
     * Delete an environment item.
     */
    public function destroy(string $id): JsonResponse
    {
        $environment = new Environment($id);
        $environment->delete();

        return response()->json([
            'message' => 'Implement env item delete logic.',
            'id' => $id,
        ], 200);
    }
}
