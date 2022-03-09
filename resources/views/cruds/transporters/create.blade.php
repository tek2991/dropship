<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create transporter') }}
            </h2>
        </span>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-validation-errors />

                    <form method="POST" action="{{ route('transporters.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="name" :value="__('Transporter Name')" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" required
                                    value="{{ old('name') }}" />
                            </div>
                            <div>
                                <x-label for="email" :value="__('Email')" />
                                <x-input id="email" class="block mt-1 w-full" type="email" name="email" required
                                    value="{{ old('email') }}" />
                            </div>

                            <div class="md:col-span-2">
                                <x-label for="address" :value="__('Address')" />
                                <x-textarea id="address" class="block mt-1 w-full" type="text" name="address" required>
                                    {{ old('address') }}</x-textarea>
                            </div>
                            <div>
                                <x-label for="phone" :value="__('Phone')" />
                                <x-input id="phone" class="block mt-1 w-full" type="number" name="phone"
                                    value="{{ old('phone') }}" required />
                            </div>
                            <div>
                                <x-label for="alternate_phone" :value="__('Phone (Alternate)')" />
                                <x-input id="alternate_phone" class="block mt-1 w-full" type="number"
                                    name="alternate_phone" value="{{ old('alternate_phone') }}" />
                            </div>
                            <div>
                                <x-label for="password" :value="__('Password')" required />
                                <x-input id="password" class="block mt-1 w-full" type="password" name="password"
                                    autocomplete="password" />
                            </div>
                            <div>
                                <x-label for="confirm_password" :value="__('Confirm password')" required />
                                <x-input id="confirm_password" class="block mt-1 w-full" type="password"
                                    name="password_confirmation" autocomplete="confirm-password" />
                            </div>
                            <div>
                                <x-label for="is_first_party" :value="__('First party')" />
                                <x-input-select id="is_first_party" class="block mt-1 w-full" name="is_first_party" required>
                                    <option value="1" {{ old('is_first_party') == true ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_first_party') != true? 'selected' : '' }}>No
                                    </option>
                                </x-input-select>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4 ">
                            <x-button class="ml-3" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
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
