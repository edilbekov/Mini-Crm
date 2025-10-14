<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
    public function index(): JsonResponse
    {
        $stats = [
            'tickets' => [
                'total' => Ticket::count(),
                'today' => Ticket::createdToday()->count(),
                'this_month' => Ticket::createdThisMonth()->count(),
                'by_status' => [
                    'new' => Ticket::new()->count(),
                    'in_progress' => Ticket::inProgress()->count(),
                    'resolved' => Ticket::resolved()->count(),
                    'closed' => Ticket::closed()->count(),
                ],
            ],
            'customers' => [
                'total' => Customer::count(),
                'with_tickets' => Customer::has('tickets')->count(),
            ],
            'managers' => [
                'total' => User::role('manager')->count(),
                'active' => User::role('manager')->has('tickets')->count(),
            ],
            'recent_tickets' => Ticket::with(['customer', 'manager'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn($ticket) => [
                    'id' => $ticket->id,
                    'subject' => $ticket->subject,
                    'status' => $ticket->status,
                    'customer' => $ticket->customer->name,
                    'created_at' => $ticket->created_at->format('Y-m-d H:i:s'),
                ]),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    public function managerStats(): JsonResponse
    {
        $managers = User::role('manager')
            ->withCount([
                'tickets',
                'tickets as new_tickets_count' => fn($q) => $q->where('status', Ticket::STATUS_NEW),
                'tickets as resolved_tickets_count' => fn($q) => $q->where('status', Ticket::STATUS_RESOLVED),
            ])
            ->get()
            ->map(fn($manager) => [
                'id' => $manager->id,
                'name' => $manager->name,
                'total_tickets' => $manager->tickets_count,
                'new_tickets' => $manager->new_tickets_count,
                'resolved_tickets' => $manager->resolved_tickets_count,
            ]);

        return response()->json([
            'success' => true,
            'data' => $managers,
        ]);
    }
}
