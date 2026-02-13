
<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Edit Announcement</h1>
                <p class="mt-1 text-sm text-gray-600">Update announcement details</p>
            </div>

            <!-- Form Card -->
            <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}">
                @csrf
                @method('PATCH')

                <div class="bg-white shadow-sm border border-gray-200">
                    <div class="border-l-4 border-primary-600 p-6">

                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-red-800 mb-1">There were errors with your submission</h3>
                                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Basic Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Announcement Details</h3>

                            <div class="grid grid-cols-1 gap-6">
                                <!-- Title -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Title <span class="text-red-600">*</span>
                                    </label>
                                    <input type="text" name="title" value="{{ old('title', $announcement->title) }}"
                                           class="w-full px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-primary-600 focus:border-transparent transition @error('title') border-red-500 @enderror"
                                           required>
                                </div>

                                <!-- Topic -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Topic <span class="text-red-600">*</span>
                                    </label>
                                    <select name="topic" id="topic"
                                            class="w-full px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-primary-600 focus:border-transparent transition @error('topic') border-red-500 @enderror"
                                            required onchange="toggleTopicFields()">
                                        <option value="">Select Topic</option>
                                        <option value="general" {{ old('topic', $announcement->topic) == 'general' ? 'selected' : '' }}>General (All Users)</option>
                                        <option value="event" {{ old('topic', $announcement->topic) == 'event' ? 'selected' : '' }}>Event-Specific</option>
                                    </select>
                                </div>

                                <!-- Event Selection (hidden by default) -->
                                <div id="event-field" style="display: {{ old('topic', $announcement->topic) == 'event' ? 'block' : 'none' }};">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Select Event <span class="text-red-600">*</span>
                                    </label>
                                    <select name="event_id" id="event-select"
                                            class="w-full px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-primary-600 focus:border-transparent transition">
                                        <option value="">Choose an event</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" {{ old('event_id', $announcement->event_id) == $event->id ? 'selected' : '' }}>
                                                {{ $event->title }} ({{ $event->start_datetime->format('M d, Y') }})
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- Auto-Generate Button for Events -->
                                    <button type="button" id="event-generate-btn" onclick="generateEventMessage()"
                                            class="mt-3 inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white text-sm font-semibold shadow-md transition-all duration-200 transform hover:scale-105">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <span id="event-generate-btn-text">Re-Generate Message</span>
                                    </button>
                                    <p class="mt-2 text-sm text-gray-600">
                                        Auto-generates a new message from the selected event (will overwrite current message)
                                    </p>
                                </div>

                                <!-- Message -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Message <span class="text-red-600">*</span>
                                    </label>
                                    <textarea name="message" id="message-textarea" rows="12"
                                              class="w-full px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-primary-600 focus:border-transparent transition @error('message') border-red-500 @enderror"
                                              required>{{ old('message', $announcement->message) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Info -->
                        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded">
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><strong>Created by:</strong> {{ $announcement->creator?->first_name }} {{ $announcement->creator?->surname ?? 'Unknown' }}</p>
                                <p><strong>Created:</strong> {{ $announcement->created_at->format('M d, Y \a\t g:i A') }}</p>
                                <p><strong>Last updated:</strong> {{ $announcement->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                            <x-buttons.primary type="submit">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Announcement
                            </x-buttons.primary>

                            <x-buttons.secondary href="{{ route('admin.announcements.index') }}">
                                Cancel
                            </x-buttons.secondary>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Delete Section -->
            <div class="mt-8 bg-white shadow-sm border border-red-200">
                <div class="border-l-4 border-red-600 p-6">
                    <h2 class="text-lg font-semibold text-red-900 mb-2">Danger Zone</h2>
                    <p class="text-sm text-gray-600 mb-4">
                        Permanently delete this announcement. This action cannot be undone.
                    </p>
                    <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}"
                          onsubmit="return confirm('Are you sure you want to delete this announcement? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <x-buttons.danger type="submit">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Announcement
                        </x-buttons.danger>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTopicFields() {
            const topic = document.getElementById('topic').value;
            const eventField = document.getElementById('event-field');

            if (topic === 'event') {
                eventField.style.display = 'block';
            } else {
                eventField.style.display = 'none';
            }
        }

        async function generateEventMessage() {
            const eventSelect = document.getElementById('event-select');
            const eventId = eventSelect.value;
            const messageTextarea = document.getElementById('message-textarea');
            const generateBtn = document.getElementById('event-generate-btn');
            const btnText = document.getElementById('event-generate-btn-text');

            if (!eventId) {
                alert('Please select an event first');
                return;
            }

            if (!confirm('This will overwrite the current message. Continue?')) {
                return;
            }

            generateBtn.disabled = true;
            btnText.innerHTML = 'Generating...';
            generateBtn.classList.add('opacity-75', 'cursor-not-allowed');

            try {
                const response = await fetch('{{ route("admin.announcements.generate-message") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        type: 'event',
                        id: eventId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    messageTextarea.value = data.message;

                    const originalClasses = generateBtn.className;
                    generateBtn.className = generateBtn.className.replace('from-primary-600 to-primary-700', 'from-green-600 to-green-700');
                    btnText.innerHTML = '✓ Message Generated!';

                    setTimeout(() => {
                        generateBtn.className = originalClasses;
                        btnText.innerHTML = 'Re-Generate Message';
                    }, 2000);
                } else {
                    alert('Failed to generate message: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while generating the message.');
            } finally {
                generateBtn.disabled = false;
                generateBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleTopicFields();
        });
    </script>
</x-app-layout>
