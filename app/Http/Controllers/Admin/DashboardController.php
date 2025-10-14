<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_tickets' => Ticket::count(),
            'today_tickets' => Ticket::createdToday()->count(),
            'new_tickets' => Ticket::new()->count(),
            'in_progress_tickets' => Ticket::inProgress()->count(),
            'resolved_tickets' => Ticket::resolved()->count(),
            'month_resolved' => Ticket::resolved()->createdThisMonth()->count(),
            'total_customers' => Customer::count(),
            'active_customers' => Customer::has('tickets')->count(),
        ];

        $recent_tickets = Ticket::with(['customer', 'manager'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_tickets'));
    }
}
