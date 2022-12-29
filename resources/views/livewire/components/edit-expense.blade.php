<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" wire:submit.prevent="update">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="vehicle_id" :value="__('Vehicle')" />
                            @error('vehicle_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            <x-input-select id="vehicle_id" class="block mt-1 w-full" wire:model="vehicle_id" required>
                                <option value="">Select</option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}"
                                        {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->registration_number }}</option>
                                @endforeach
                            </x-input-select>
                        </div>
                        <div>
                            <x-label for="date" :value="__('Date')" />
                            @error('date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            <x-input id="date" class="block mt-1 w-full" type="date" name="date" required
                                wire:model="date" />
                        </div>

                        @if ($invoices != null)
                            <div class="md:col-span-2">
                                <x-label for="invoice_id" :value="__('Destinations (clients)')" />
                                <ul class="list-disc my-3">
                                    @foreach ($invoices as $invoice)
                                        <li class="ml-4">
                                            {{ $invoice->client->user->name }}
                                        </li>
                                    @endforeach
                                </ul>
                                <span>
                                    Total: {{ $invoices->count() }}
                                </span>
                            </div>
                        @endif

                        <div>
                            <x-label for="amount" :value="__('Amount')" />
                            @error('amount')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            <x-input id="amount" class="block mt-1 w-full" type="number" name="amount" required
                                wire:model="amount" />
                        </div>

                        <div class="md:col-span-2">
                            <x-label for="remark" :value="__('Remark')" />
                            @error('remark')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            <x-input id="remark" class="block mt-1 w-full" type="text" name="remark" required
                                wire:model="remark" />
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-4 ">
                        <x-button class="ml-3" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            {{ __('Update') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
