<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Expense') }}
            </h2>
        </span>
    </x-slot>

    @livewire('components.create-expense')
</x-app-layout>
