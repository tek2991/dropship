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

                    <form method="POST" action="{{ route('admin.vehicles.update', ['vehicle' => $vehicle->id]) }}">
                        @method('PUT')
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="registration_number" :value="__('Registration Number')" />
                                <x-input id="registration_number" class="block mt-1 w-full" type="text"
                                    name="registration_number" required value="{{ $vehicle->registration_number }}" />
                            </div>
                            <div>
                                <x-label for="status" :value="__('Status')" />
                                <x-input-select id="status" class="block mt-1 w-full" name="is_active" required>
                                    <option value="1" {{ $vehicle->is_active == true ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ $vehicle->is_active != true ? 'selected' : '' }}>
                                        Inactive
                                    </option>
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

                    <h2 class="font-semibold text-xl text-gray-800 leading-tight my-6">Assigned Locations</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <ol class="list-decimal pl-4">
                            @foreach ($vehicle->locations as $location)
                                <li>
                                    <form
                                        action="{{ route('admin.vehicles.remove.location', ['vehicle' => $vehicle->id]) }}"
                                        method="post">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="location_id" value="{{ $location->id }}">
                                        <div class="flex items-center justify-between">
                                            <h3><strong>{{ $location->name }}</strong>
                                            </h3>
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </form>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    <h2 class="font-semibold text-xl text-gray-800 leading-tight my-6 pt-6">Add Location</h2>
                    <form method="POST"
                        action="{{ route('admin.vehicles.add.location', ['vehicle' => $vehicle->id]) }}">
                        @method('PUT')
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="location_id" :value="__('Select Location')" />
                                <x-input-select id="location_id" class="block mt-1 w-full" type="text"
                                    name="location_id">
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
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
                                {{ __('Create') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
