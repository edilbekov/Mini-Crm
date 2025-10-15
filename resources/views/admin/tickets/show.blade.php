@extends('layouts.admin')

@section('title', 'Заявка #' . $ticket->id)
@section('header', 'Заявка #' . $ticket->id)

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Назад
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Details -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $ticket->subject }}</h2>
                            <p class="text-sm text-gray-500 mt-1">
                                Создано успешно: {{ $ticket->created_at->format('d.m.Y H:i') }}
                            </p>
                        </div>
                        @php
                            $statusColors = [
                                'new' => 'bg-orange-100 text-orange-800',
                                'in_progress' => 'bg-blue-100 text-blue-800',
                                'resolved' => 'bg-green-100 text-green-800',
                                'closed' => 'bg-gray-100 text-gray-800',
                            ];
                            $statusLabels = [
                                'new' => 'Новый',
                                'in_progress' => 'В процессе',
                                'resolved' => 'Решено',
                                'closed' => 'Закрыто',
                            ];
                        @endphp
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$ticket->status] }}">
                            {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Ta'rif:</h3>
                    <p class="text-gray-900 whitespace-pre-line">{{ $ticket->description }}</p>
                    <div class="mt-4">
                        <a href="{{ route('admin.tickets.edit', $ticket) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Редактировать
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Данные клиента</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Имя:</p>
                        <p class="text-gray-900">{{ $ticket->customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Телефон:</p>
                        <p class="text-gray-900">{{ $ticket->customer->phone }}</p>
                    </div>
                    @if($ticket->customer->email)
                    <div>
                        <p class="text-sm font-medium text-gray-700">Электронная почта:</p>
                        <p class="text-gray-900">{{ $ticket->customer->email }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Manager Info -->
            @if($ticket->manager)
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Ответственный менеджер</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Имя:</p>
                        <p class="text-gray-900">{{ $ticket->manager->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Электронная почта:</p>
                        <p class="text-gray-900">{{ $ticket->manager->email }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
