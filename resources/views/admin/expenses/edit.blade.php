<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit vehicle') }}
            </h2>
        </span>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-validation-errors />

                    <form method="POST" action="{{ route('admin.expenses.update', ['expense' => $expense->id]) }}">
                        @method('PUT')
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="vehicle_id" :value="__('Vehicle')" />
                                <x-input-select id="vehicle_id" class="block mt-1 w-full" name="vehicle_id" required>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}"
                                            {{ $expense->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->registration_number }}</option>
                                    @endforeach
                                </x-input-select>
                            </div>
                            <div>
                                <x-label for="amount" :value="__('Amount')" />
                                <x-input id="amount" class="block mt-1 w-full" type="number" name="amount" required
                                    value="{{ $expense->amount }}" />
                            </div>
                            <div>
                                <x-label for="remark" :value="__('Remark')" />
                                <x-input id="remark" class="block mt-1 w-full" type="text" name="remark" required
                                    value="{{ $expense->remark }}" />
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
