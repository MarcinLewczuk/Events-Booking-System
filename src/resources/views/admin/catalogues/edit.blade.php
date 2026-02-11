<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Edit Catalogue</h1>
                <p class="mt-1 text-sm text-gray-600">Update catalogue details and manage items</p>
            </div>

            <!-- Catalogue Info Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="border-l-4 border-[#370671] p-6">
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-red-800 mb-1">There were errors</h3>
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-green-800 font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Status Banner -->
                    <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-[#370671] to-purple-600 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $catalogue->name }}</h3>
                                    <p class="text-sm text-gray-600">
                                        Status: <span class="font-semibold text-[#370671]">{{ ucfirst($catalogue->status) }}</span>
                                        · Items: <span class="font-semibold">{{ $items->count() }}/90</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.catalogues.update', $catalogue) }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Catalogue Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Catalogue Name <span class="text-red-600">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       value="{{ old('name', $catalogue->name) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition"
                                       required>
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Category <span class="text-red-600">*</span>
                                </label>
                                <select name="category_id" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition"
                                        required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $catalogue->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Status
                            </label>
                            <select name="status" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent transition"
                                    {{ $catalogue->status === 'published' ? 'disabled' : '' }}>
                                <option value="draft" {{ old('status', $catalogue->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="awaiting_approval" {{ old('status', $catalogue->status) == 'awaiting_approval' ? 'selected' : '' }}>Awaiting Approval</option>
                                @if($catalogue->status === 'published')
                                    <option value="published" selected>Published (Cannot be changed)</option>
                                @endif
                            </select>
                            @if($catalogue->status === 'published')
                                <p class="mt-1 text-xs text-gray-600">Published catalogues cannot have their status changed</p>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3 pt-4 border-t border-gray-200">
                            <x-buttons.primary type="submit">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Details
                            </x-buttons.primary>
                            <x-buttons.secondary href="{{ route('admin.catalogues.index') }}">
                                Back to List
                            </x-buttons.secondary>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Add Item to Catalogue -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="border-l-4 border-green-600 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Item to Catalogue</h3>
                    
                    @if($items->count() >= 90)
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Maximum capacity reached (90 items). Remove items before adding more.
                            </p>
                        </div>
                    @elseif($availableItems->isEmpty())
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <p class="text-sm text-gray-600">No available items to add. All catalogue-ready items are already in this catalogue.</p>
                        </div>
                    @else
                    <form method="POST" action="{{ route('admin.catalogues.addItem', $catalogue) }}" id="addItemForm" class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-lg">
                        @csrf
                        <h4 class="font-semibold text-gray-900 mb-3">Add Item to Catalogue</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Item</label>
                                <select name="item_id" id="itemSelect" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent text-sm">
                                    <option value="">Select an item</option>
                                    @foreach($availableItems as $item)
                                        <option value="{{ $item->id }}" data-item-id="{{ $item->id }}">
                                            {{ $item->title }} ({{ $item->category?->name ?? 'Uncategorized' }}) - Ref: {{ $item->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Lot Number
                                    <span class="text-xs text-green-600 ml-1">(Auto-generated)</span>
                                </label>
                                <input type="number" 
                                    name="lot_number" 
                                    id="lotNumber"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#370671] focus:border-transparent text-sm bg-green-50"
                                    placeholder="Auto"
                                    readonly>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <p class="text-xs text-gray-600">
                                <span class="font-semibold">{{ $catalogue->items->count() }}</span> / 90 items in catalogue
                                <span class="ml-2 text-green-600">• Lot number auto-generated • Reference = Item ID</span>
                            </p>
                            <x-buttons.primary type="submit">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add Item
                            </x-buttons.primary>
                        </div>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Current Items -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="border-l-4 border-blue-600 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Items in Catalogue ({{ $items->count() }})</h3>
                    
                    @if($items->isEmpty())
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-gray-500">No items in this catalogue yet</p>
                            <p class="text-sm text-gray-400 mt-1">Add items using the form above</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lot #</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Ref</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Est. Price</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($catalogue->items as $item)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $item->pivot->display_order }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item->title }}</div>
                                                    @if($item->short_description)
                                                        <div class="text-xs text-gray-500">{{ Str::limit($item->short_description, 50) }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $item->category?->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $item->pivot->lot_number ?? 'N/A' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-mono">
                                                    {{ $item->id }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    @if($item->estimated_price)
                                                        £{{ number_format($item->estimated_price, 2) }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                                    <form method="POST" action="{{ route('admin.catalogues.removeItem', [$catalogue, $item]) }}" 
                                                        onsubmit="return confirm('Remove this item from the catalogue?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                                            Remove
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                    @endif
                </div>
            </div>

            <!-- Delete Section -->
            <div class="mt-8 bg-white rounded-lg shadow-sm border border-red-200">
                <div class="border-l-4 border-red-600 p-6">
                    <h2 class="text-lg font-semibold text-red-900 mb-2">Danger Zone</h2>
                    <p class="text-sm text-gray-600 mb-4">
                        Permanently delete this catalogue and remove all item associations.
                    </p>
                    <form method="POST" 
                          action="{{ route('admin.catalogues.destroy', $catalogue) }}"
                          onsubmit="return confirm('Are you sure you want to delete this catalogue? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <x-buttons.danger type="submit">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Catalogue
                        </x-buttons.danger>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for auto-generating lot numbers -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const itemSelect = document.getElementById('itemSelect');
            const lotNumber = document.getElementById('lotNumber');

            // Get existing lot numbers from the table
            const existingLotNumbers = new Set();
            
            @foreach($catalogue->items as $item)
                @if($item->pivot->lot_number)
                    existingLotNumbers.add({{ $item->pivot->lot_number }});
                @endif
            @endforeach

            // Function to find next available number
            function getNextAvailableNumber(existingSet, startFrom = 1) {
                let number = startFrom;
                while (existingSet.has(number)) {
                    number++;
                }
                return number;
            }

            // When an item is selected, auto-generate lot number
            itemSelect.addEventListener('change', function() {
                if (this.value) {
                    // Generate unique lot number (starting from 1)
                    const nextLotNumber = getNextAvailableNumber(existingLotNumbers, 1);
                    lotNumber.value = nextLotNumber;

                    // Add to set to prevent duplicates in same session
                    existingLotNumbers.add(nextLotNumber);

                    // Visual feedback
                    lotNumber.classList.add('ring-2', 'ring-green-500');
                    
                    setTimeout(() => {
                        lotNumber.classList.remove('ring-2', 'ring-green-500');
                    }, 1000);
                } else {
                    lotNumber.value = '';
                }
            });
        });
    </script>
</x-app-layout>