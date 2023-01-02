<div>
    <div class="max-w-md mb-4">
        <x-label for="vehicle_id" :value="__('Vehicle')" />
        <x-input-select id="vehicle_id" class="block mt-1 w-full" wire:model="vehicle_id" required>
            <option value="">Select</option>
            @foreach ($vehicles as $vehicle)
                <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                    {{ $vehicle->registration_number }}</option>
            @endforeach
        </x-input-select>
    </div>

    @livewire('pending-invoice-table', ['vehicle_id' => $vehicle_id ? $vehicle_id : ''])
</div>
