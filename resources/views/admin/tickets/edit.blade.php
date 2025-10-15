@extends('layouts.admin')

@section('title', 'Редактировать заявку #' . $ticket->id)
@section('header', 'Редактировать заявку #' . $ticket->id)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.tickets.show', $ticket) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Назад
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Subject -->
            <div class="md:col-span-2">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                    Тема *
                </label>
                <input
                    type="text"
                    id="subject"
                    name="subject"
                    value="{{ old('subject', $ticket->subject) }}"
                    required
                    maxlength="255"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('subject') border-red-500 @enderror"
                >
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Статус *
                </label>
                <select
                    id="status"
                    name="status"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                >
                    <option value="new" {{ old('status', $ticket->status) == 'new' ? 'selected' : '' }}>Новый</option>
                    <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>В процессе</option>
                    <option value="resolved" {{ old('status', $ticket->status) == 'resolved' ? 'selected' : '' }}>Решено</option>
                    <option value="closed" {{ old('status', $ticket->status) == 'closed' ? 'selected' : '' }}>Закрыто</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Manager -->
            <div>
                <label for="manager_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Менеджер
                </label>
                <select
                    id="manager_id"
                    name="manager_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('manager_id') border-red-500 @enderror"
                >
                    <option value="">Tanlanmagan</option>
                    @foreach($managers as $manager)
                    <option value="{{ $manager->id }}" {{ old('manager_id', $ticket->manager_id) == $manager->id ? 'selected' : '' }}>
                        {{ $manager->name }}
                    </option>
                    @endforeach
                </select>
                @error('manager_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Ta'rif *
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="6"
                    required
                    maxlength="1000"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                >{{ old('description', $ticket->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Customer Info (Read-only) -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="text-sm font-medium text-gray-700 mb-3">Данные клиента</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-medium">Имя:</span> {{ $ticket->customer->name }}
                </div>
                <div>
                    <span class="font-medium">Телефон:</span> {{ $ticket->customer->phone }}
                </div>
                <div>
                    <span class="font-medium">Электронная почта:</span> {{ $ticket->customer->email ?: 'Kiritilmagan' }}
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.tickets.show', $ticket) }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                Отменить
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Сохранить
            </button>
        </div>
    </form>
</div>
@endsection
