<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-xl p-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center shadow-md">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                {{ $eventId ? 'Edit Event' : 'Create New Event' }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ $eventId ? 'Modify the details below' : 'Fill in the details to schedule your event' }}
            </p>
        </div>

        <!-- Success Message -->
        @if (session()->has('success'))
            <div class="rounded-lg bg-green-50 p-4 border border-green-200 mt-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form wire:submit="save" class="mt-8 space-y-6">
            <!-- Title Field -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Event Title</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 20h10M5 20h14M5 4h14M5 4v16M19 4v16M9 8h6M9 12h6M9 16h6" />
                        </svg>
                    </div>
                    <input type="text" id="title" wire:model.live="title"
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                        placeholder="e.g., Team Meeting, Birthday Party, Conference">
                </div>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
<!-- Description Field with Summernote -->
<div wire:ignore>
    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
        Description <span class="text-gray-400 text-xs">(optional)</span>
    </label>
    <textarea id="summernote-description"
              class="block w-full border border-gray-300 rounded-lg"></textarea>
    @error('description')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
            <!-- Date & Time Field -->
            <div>
                <label for="event_time" class="block text-sm font-medium text-gray-700">Event Date & Time</label>
                <input type="datetime-local" wire:model="event_time" id="event_time"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('event_time')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- ✅ is_public Toggle (Checkbox) -->
            <div class="flex items-center">
                <input type="checkbox" id="is_public" wire:model="is_public"
                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="is_public" class="ml-2 block text-sm text-gray-700">
                    Make this event public (visible to everyone without login)
                </label>
            </div>
            @error('is_public')
                <p class="text-red-500 text-xs">{{ $message }}</p>
            @enderror

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.events.index') }}"
                    class="inline-flex items-center px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg text-sm font-semibold text-white shadow-md hover:shadow-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ $eventId ? 'Update Event' : 'Create Event' }}
                </button>
            </div>
        </form>
    </div>
</div>
@push('scripts')

@endpush
