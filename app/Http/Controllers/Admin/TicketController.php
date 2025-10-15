<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['customer', 'manager']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by search (customer name, email, phone or ticket subject)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['customer', 'manager', 'history.user']);

        return view('admin.tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $managers = User::role('manager')->get();

        return view('admin.tickets.edit', compact('ticket', 'managers'));
    }

    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'status' => 'required|in:' . implode(',', Ticket::getStatuses()),
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $oldStatus = $ticket->status;
        $ticket->update($request->only(['subject', 'description', 'manager_id']));

        // Change status if different
        if ($oldStatus !== $request->status) {
            $ticket->changeStatus($request->status, auth()->id());
        }

        return redirect()->route('admin.tickets.show', $ticket)
                        ->with('success', 'Zayavka muvaffaqiyatli yangilandi!');
    }

    public function changeStatus(Request $request, Ticket $ticket): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', Ticket::getStatuses()),
        ]);

        $ticket->changeStatus($request->status, auth()->id());

        return redirect()->back()->with('success', 'Status muvaffaqiyatli o\'zgartirildi!');
    }
}
