@extends('layouts.admin')

@section('title', 'Пользователь: ' . $user->name)
@section('header', 'Пользователь: ' . $user->name)

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Назад
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Details -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Зарегистрированный: {{ $user->created_at->format('d.m.Y H:i') }}
                    </p>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Электронная почта:</p>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Телефон:</p>
                            <p class="text-gray-900">{{ $user->phone ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Роль:</p>
                            <p class="text-gray-900">
                                @if($user->hasRole('admin'))
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Админ</span>
                                @elseif($user->hasRole('manager'))
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Менеджер</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">Пользователь</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Всего заявок:</p>
                            <p class="text-gray-900">{{ $user->tickets->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tickets -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Последние заявки</h3>
                </div>
                <div class="p-6">
                    @if($user->tickets->count() > 0)
                    <div class="space-y-4">
                        @foreach($user->tickets as $ticket)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $ticket->subject }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($ticket->description, 100) }}</p>
                                    <p class="text-xs text-gray-500 mt-2">{{ $ticket->created_at->format('d.m.Y H:i') }}</p>
                                    <p class="text-xs text-gray-500">Клиент: {{ $ticket->customer->name }}</p>
                                </div>
                                <div class="ml-4">
                                    @php
                                        $statusColors = [
                                            'new' => 'bg-orange-100 text-orange-800',
                                            'in_progress' => 'bg-blue-100 text-blue-800',
                                            'resolved' => 'bg-green-100 text-green-800',
                                            'closed' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $statusLabels = [
                                            'new' => 'Yangi',
                                            'in_progress' => 'Jarayonda',
                                            'resolved' => 'Hal qilindi',
                                            'closed' => 'Yopildi',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$ticket->status] }}">
                                        {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Просмотр →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-8">У этого пользователя пока нет заявок</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Статистика</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $user->tickets->count() }}</div>
                        <div class="text-sm text-gray-600">Всего заявок</div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <div class="text-xl font-semibold text-orange-600">{{ $user->tickets->where('status', 'new')->count() }}</div>
                            <div class="text-xs text-gray-600">Новый</div>
                        </div>
                        <div>
                            <div class="text-xl font-semibold text-blue-600">{{ $user->tickets->where('status', 'in_progress')->count() }}</div>
                            <div class="text-xs text-gray-600">В процессе</div>
                        </div>
                        <div>
                            <div class="text-xl font-semibold text-green-600">{{ $user->tickets->where('status', 'resolved')->count() }}</div>
                            <div class="text-xs text-gray-600">Решено</div>
                        </div>
                        <div>
                            <div class="text-xl font-semibold text-gray-600">{{ $user->tickets->where('status', 'closed')->count() }}</div>
                            <div class="text-xs text-gray-600">Закрыто</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
