<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Profile Details') }}
            </h2>
            <x-button-link href="{{ route('user.edit', ['user' => Auth::user()->id]) }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Update</span>
            </x-button-link>
        </span>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-success-message />
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <h3><strong>Name: </strong>{{ $user->name }}</h3>
                        <h3 class="md:text-right"><strong>Status: </strong>
                            @if ($user->is_active == true)
                                <span class="text-green-500">Active <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 inline mb-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg></span>
                            @else
                                <span class="text-red-500">Inactive <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 inline mb-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg></span>
                            @endif
                        </h3>
                        <h3 class="md:col-span-2">
                            <strong>Address: </strong>{{ $user->address }}
                        </h3>
                        <h3>
                            <strong>Email: </strong>{{ $user->email }}
                        </h3>
                        <h3 class="md:text-right">
                            <strong>Phone:
                            </strong>{{ $user->phone }}{{ $user->alternate_phone ? ',  ' . $user->alternate_phone : '' }}
                        </h3>
                        <h3>
                            <strong>
                                D.O.B:
                            </strong>{{ $user->dob ? $user->dob->format('Y-m-d') : 'NA' }}
                        </h3>
                        <h3 class="md:text-right">
                            <strong>
                                Gender:
                            </strong>{{ $user->gender ? $user->gender : 'NA' }}
                        </h3>
                        <h3 class="flex">
                            <strong>
                               Roles:
                            </strong>
                            <ul class="pl-8 list-disc">
                                @forelse ($user->roles as $role)
                                    <li>{{ $role->name }}</li>
                                @empty
                                    <li class="text-orange-400">No roles</li>
                                @endforelse
                            </ul>
                        </h3>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
