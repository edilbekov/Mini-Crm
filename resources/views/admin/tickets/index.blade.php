@extends('layouts.admin')

@section('title', 'Заявки')
@section('header', 'Заявки')

@section('content')
<div class="mb-6 space-y-4">
    <!-- Search and Filters -->
    <form method="GET" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Qidirish (mavzu, mijoz nomi, email yoki telefon)..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            >
        </div>
        <div class="flex gap-2">
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Barcha statuslar</option>
                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Yangi</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Jarayonda</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Hal qilindi</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Yopildi</option>
            </select>
            <input
                type="date"
                name="date_from"
                value="{{ request('date_from') }}"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                placeholder="Dan"
            >
            <input
                type="date"
                name="date_to"
                value="{{ request('date_to') }}"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                placeholder="Gacha"
            >
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Filtrlash
            </button>
            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
            <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Tozalash
            </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mijoz</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mavzu</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Manager</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sana</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amallar</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($tickets as $ticket)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $ticket->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->customer->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($ticket->subject, 40) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                        $statusColors = [
                            'new' => 'bg-orange-100 text-orange-800',
                            'in_progress' => 'bg-blue-100 text-blue-800',
                            'resolved' => 'bg-green-100 text-green-800',
                            'closed' => 'bg-gray-100 text-gray-800',
                        ];
                        $statusLabels = [
                            'new' => 'Новая',
                            'in_progress' => 'В процессе',
                            'resolved' => 'Решена',
                            'closed' => 'Закрыта',
                        ];
                    @endphp
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$ticket->status] ?? 'bg-gray-100' }}">
                        {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $ticket->manager->name ?? '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $ticket->created_at->format('d.m.Y H:i') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900">
                        Ko'rish
                    </a>
                    <a href="{{ route('admin.tickets.edit', $ticket) }}" class="text-green-600 hover:text-green-900">
                        Tahrirlash
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-2">Заявки topilmadi</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($tickets->hasPages())
<div class="mt-6">
    {{ $tickets->links() }}
</div>
@endif
@endsection
