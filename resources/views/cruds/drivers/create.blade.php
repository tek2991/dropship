<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create driver') }}
            </h2>
        </span>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-validation-errors />

                    <form method="POST" action="{{ route('drivers.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="name" :value="__('Name')" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" required
                                    value="{{ old('name') }}" />
                            </div>
                            <div>
                                <x-label for="email" :value="__('Email')" />
                                <x-input id="email" class="block mt-1 w-full" type="email" name="email" required
                                    value="{{ old('email') }}" />
                            </div>
                            <div>
                                <x-label for="gender" :value="__('Gender')" />
                                <x-input-select id="gender" class="block mt-1 w-full" name="gender" required>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                    </option>
                                </x-input-select>
                            </div>
                            <div>
                                <x-label for="dob" :value="__('D.O.B')" />
                                <x-input id="dob" class="block mt-1 w-full" type="date" name="dob" required
                                    value="{{ old('dob') }}" />
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
                        </div>
                        <div class="flex items-center justify-end mt-4 ">
                            <x-button class="ml-3" type="submit">
                                {{ __('Create') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
