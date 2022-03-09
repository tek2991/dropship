<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Profile') }}
            </h2>
        </span>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-validation-errors />

                    <form method="POST" action="{{ route('user.update', ['user' => Auth::user()->id]) }}">
                        @method('PUT')
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="name" :value="__('Name')" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" required
                                    value="{{ $user->name }}" />
                            </div>
                            <div>
                                <x-label for="email" :value="__('Email *uneditable*')" />
                                <x-input id="email" class="block mt-1 w-full" type="email" required
                                    value="{{ $user->email }}" disabled />
                            </div>
                            <div>
                                <x-label for="gender" :value="__('Gender')" />
                                <x-input-select id="gender" class="block mt-1 w-full" name="gender" required>
                                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male
                                    </option>
                                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>
                                        Female
                                    </option>
                                    <option value="" {{ $user->gender == null ? 'selected' : '' }}>Not Applicable</option>
                                </x-input-select>
                            </div>
                            <div>
                                <x-label for="dob" :value="__('D.O.B')" />
                                <x-input id="dob" class="block mt-1 w-full" type="date" name="dob" required
                                    value="{{ $user->dob ? $user->dob->format('Y-m-d') : '' }}" />
                            </div>
                            <div class="md:col-span-2">
                                <x-label for="address" :value="__('Address')" />
                                <x-textarea id="address" class="block mt-1 w-full" type="text" name="address" required>
                                    {{ $user->address }}</x-textarea>
                            </div>
                            <div>
                                <x-label for="phone" :value="__('Phone')" />
                                <x-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                                    value="{{ $user->phone }}" required />
                            </div>
                            <div>
                                <x-label for="alternate_phone" :value="__('Phone (Alternate)')" />
                                <x-input id="alternate_phone" class="block mt-1 w-full" type="text"
                                    name="alternate_phone" value="{{ $user->alternate_phone }}" />
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

                    <form method="POST" action="{{ route('user.update-password', ['user' => Auth::user()->id]) }}">
                        @method('PUT')
                        @csrf
                        <h3 class="text-lg my-8 font-bold">Update Password</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <x-label for="current_password" :value="__('Current Password')" required />
                                <x-input id="current_password" class="block mt-1 w-full" type="password" name="current_password"
                                    autocomplete="password" />
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Update Password') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
