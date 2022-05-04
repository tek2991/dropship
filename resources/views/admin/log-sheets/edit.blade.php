<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Log Sheet') }}
            </h2>
        </span>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-validation-errors />

                    <form method="POST"
                        action="{{ route('admin.log-sheets.update', ['log_sheet' => $logSheet->id]) }}">
                        @method('PUT')
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="vehicle" :value="__('Vehicle')" />
                                <x-input-select id="vehicle" class="block mt-1 w-full" name="vehicle_id" required>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}"
                                            {{ $vehicle->id == $logSheet->vehicle_id ? 'selected' : '' }}>
                                            {{ $vehicle->registration_number }}
                                        </option>
                                    @endforeach
                                </x-input-select>
                            </div>
                            <div>
                                <x-label for="driver" :value="__('Driver')" />
                                <x-input-select id="driver" class="block mt-1 w-full" name="driver_id" required>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}"
                                            {{ $driver->id == $logSheet->driver_id ? 'selected' : '' }}>
                                            {{ $driver->user->name }}
                                        </option>
                                    @endforeach
                                </x-input-select>
                            </div>
                            <div>
                                <x-label for="transporter" :value="__('Transporter')" />
                                <x-input-select id="transporter" class="block mt-1 w-full" name="transporter_id" required>
                                    @foreach ($transporters as $transporter)
                                        <option value="{{ $transporter->id }}"
                                            {{ $transporter->id == $logSheet->transporter_id ? 'selected' : '' }}>
                                            {{ $transporter->user->name }}
                                        </option>
                                    @endforeach
                                </x-input-select>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4 ">
                            <x-button class="ml-3" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Update') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
