<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $status = $request->query("status");

        $query = Subscription::query();

        // Menyaring berdasarkan status jika diperlukan
        if ($status !== null) {
            if (!in_array($status, ["active", "inactive"], true)) {
                return response()->json([
                    "success" => false,
                    "message" => "Validation failed",
                    "errors" => [
                        "status" => ["The selected status is invalid."],
                    ],
                ], 422);
            }

            $query->where("status", $status === "active");
        }

        $subscriptions = $query->latest()->get();

        return response()->json([
            "success" => true,
            "message" => "Subscriptions retrieved successfully",
            "data" => $subscriptions,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            "customer_id" => ["required", "integer"],
            "service_id" => ["required", "integer"],
            "start_date" => ["required", "date"],
            "end_date" => ["nullable", "date", "after:start_date"],
            "status" => ["nullable", "boolean"],
        ]);

        $data["status"] = $data["status"] ?? true;

        $subscription = Subscription::query()->create($data);

        return response()->json([
            "success" => true,
            "message" => "Subscription created successfully",
            "data" => $subscription,
        ], 201);
    }

    public function show(int $subscription): JsonResponse
    {
        $subscriptionData = Subscription::query()->find($subscription);

        if (!$subscriptionData) {
            return response()->json([
                "success" => false,
                "message" => "Subscription not found",
                "errors" => [],
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Subscription retrieved successfully",
            "data" => $subscriptionData,
        ]);
    }

    public function update(Request $request, int $subscription): JsonResponse
    {
        $subscriptionData = Subscription::query()->find($subscription);

        if (!$subscriptionData) {
            return response()->json([
                "success" => false,
                "message" => "Subscription not found",
                "errors" => [],
            ], 404);
        }

        $data = $request->validate([
            "customer_id" => ["sometimes", "integer"],
            "service_id" => ["sometimes", "integer"],
            "start_date" => ["sometimes", "date"],
            "end_date" => ["sometimes", "date", "after:start_date"],
            "status" => ["nullable", "boolean"],
        ]);

        $subscriptionData->update($data);

        return response()->json([
            "success" => true,
            "message" => "Subscription updated successfully",
            "data" => $subscriptionData,
        ]);
    }

    public function destroy(int $subscription): JsonResponse
    {
        $subscriptionData = Subscription::query()->find($subscription);

        if (!$subscriptionData) {
            return response()->json([
                "success" => false,
                "message" => "Subscription not found",
                "errors" => [],
            ], 404);
        }

        $subscriptionData->delete();

        return response()->json([
            "success" => true,
            "message" => "Subscription deleted successfully",
            "data" => null,
        ]);
    }
}