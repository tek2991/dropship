<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reports') }}
            </h2>
        </span>
    </x-slot>

    @if (Auth::user()->hasRole('admin'))
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <script>
                            submit(){
                                document.getElementById('vehicle_selector').submit();
                            }
                        </script>
                        <form action="{{ route('admin.reports.index') }}" method="get" id="vehicle_selector">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <x-label for="vehicle_id" :value="__('Vehicle')" />
                                    @error('vehicle_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                    <x-input-select id="vehicle_id" class="block mt-1 w-full" name="vehicle_id" onchange="submit()"
                                        required>
                                        <option value="">Select</option>
                                        @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}"
                                                {{ old('vehicle_id') == $vehicle->id || $vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                                {{ $vehicle->registration_number }}</option>
                                        @endforeach
                                    </x-input-select>
                                </div>
                                <div>
                                    <x-label for="location_id" :value="__('Location')" />
                                    @error('location_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                    <x-input-select id="location_id" class="block mt-1 w-full" name="location_id" onchange="submit()"
                                        required>
                                        <option value="">Select</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}"
                                                {{ old('location_id') == $location->id || $location_id == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}</option>
                                        @endforeach
                                    </x-input-select>
                                </div>
                            </div>
                        </form>
                        @livewire('pending-invoice-table', ['vehicle_id' => $vehicle_id, 'location_id' => $location_id])
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
