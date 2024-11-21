<x-filament::page>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Notifications</h2>

        @if ($this->getNotifications()->isEmpty())
            <p class="text-gray-500">No notifications found.</p>
        @else
            <ul class="space-y-4">
                @foreach ($this->getNotifications() as $notification)
                    <li x-data="{ showDetails: false }" class="p-4 bg-gray-100 border rounded-lg shadow-sm">
                        <div class="mb-2">
                            <strong class="text-lg">{{ $notification->data['title'] ?? 'No title' }}</strong>
                        </div>

                        <p class="text-gray-700">{{ $notification->data['body'] ?? '' }}</p>

                        <!-- Details Section -->
                        <div x-show="showDetails" class="mt-4 text-gray-700">
                            @if (isset($notification->data['registration_data']))
                                @foreach ($notification->data['registration_data'] as $key => $value)
                                    <!-- Exclude 'id', 'created_at', and 'updated_at' -->
                                    @if (!in_array($key, ['id', 'created_at', 'updated_at']))
                                        <p><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                            {{ $value }}</p>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <!-- Toggle Button and Action Buttons -->
                        <div class="flex items-center justify-between mt-4">
                            <button @click="showDetails = !showDetails" class="text-blue-500 hover:underline">
                                <span x-show="!showDetails">Show Details</span>
                                <span x-show="showDetails">Hide Details</span>
                            </button>

                            <!-- Approval and Rejection Buttons with SVG Icons, Inline Styles, and Adjusted Size -->
                            <div class="flex space-x-6"> <!-- Increased spacing between buttons -->
                                <!-- Approve Button with Check Icon -->
                                <button wire:click="approveNotification('{{ $notification->id }}')"
                                    class="flex items-center space-x-2 font-bold text-sm px-3 py-1 rounded"
                                    style="background-color: #10B981; color: white;"
                                    onclick="setTimeout(function(){ location.reload(); }, 3000);">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Approve</span>
                                </button>



                                <!-- Reject Button with X Icon -->
                                <button wire:click="rejectNotification('{{ $notification->id }}')"
                                    class="flex items-center space-x-2 font-bold text-sm px-3 py-1 rounded"
                                    style="background-color: #EF4444; color: white;"
                                    onclick="setTimeout(function(){ location.reload(); }, 3000);">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span>Reject</span>
                                </button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-filament::page>
