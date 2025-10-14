<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Ticket::with(['customer', 'manager']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by manager
        if ($request->has('manager_id')) {
            $query->where('manager_id', $request->manager_id);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $tickets = $query->latest()->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => TicketResource::collection($tickets),
            'meta' => [
                'total' => $tickets->total(),
                'per_page' => $tickets->perPage(),
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
            ],
        ]);
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = Ticket::create($request->validated());
        $ticket->load(['customer', 'manager']);

        return response()->json([
            'success' => true,
            'message' => 'Zayavka yaratildi',
            'data' => new TicketResource($ticket),
        ], 201);
    }

    public function show(Ticket $ticket): JsonResponse
    {
        $ticket->load(['customer', 'manager', 'history.user']);

        return response()->json([
            'success' => true,
            'data' => new TicketResource($ticket),
        ]);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket): JsonResponse
    {
        $ticket->update($request->validated());
        $ticket->load(['customer', 'manager']);

        return response()->json([
            'success' => true,
            'message' => 'Zayavka yangilandi',
            'data' => new TicketResource($ticket->fresh()),
        ]);
    }

    public function destroy(Ticket $ticket): JsonResponse
    {
        $ticket->delete();

        return response()->json([
            'success' => true,
            'message' => 'Zayavka o\'chirildi',
        ]);
    }

    public function changeStatus(Request $request, Ticket $ticket): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:' . implode(',', Ticket::getStatuses())],
        ]);

        $ticket->changeStatus($request->status, $request->user()?->id);
        $ticket->load(['customer', 'manager', 'history.user']);

        return response()->json([
            'success' => true,
            'message' => 'Status o\'zgartirildi',
            'data' => new TicketResource($ticket),
        ]);
    }
}
