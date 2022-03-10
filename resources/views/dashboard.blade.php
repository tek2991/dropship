<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (auth()->user()->hasRole('admin'))
                        @php
                            $counts = [
                                'drivers' => [
                                    'title' => 'Drivers',
                                    'count' => \App\Models\Driver::count(),
                                    'url' => route('admin.drivers.index'),
                                ],
                                'clients' => [
                                    'title' => 'Clients',
                                    'count' => \App\Models\Client::count(),
                                    'url' => route('admin.clients.index'),
                                ],
                                'vehicles' => [
                                    'title' => 'Vehicles',
                                    'count' => \App\Models\Vehicle::count(),
                                    'url' => route('admin.vehicles.index'),
                                ],
                                'transporters' => [
                                    'title' => 'Transporters',
                                    'count' => \App\Models\Transporter::count(),
                                    'url' => route('admin.transporters.index'),
                                ],
                                'imports' => [
                                    'title' => 'Imports',
                                    'count' => \App\Models\Import::count(),
                                    'url' => route('admin.imports.index'),
                                ],
                                'log_sheets' => [
                                    'title' => 'Log Sheets',
                                    'count' => \App\Models\LogSheet::count(),
                                    'url' => '#',
                                ],
                                'invoices' => [
                                    'title' => 'Invoices',
                                    'count' => \App\Models\Invoice::count(),
                                    'url' => '#',
                                ],
                            ];
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($counts as $item => $data)
                                <a href="{{ $data['url'] }}">
                                    <div class="flex items-center p-4 bg-gray-100 rounded-lg shadow-xs dark:bg-gray-800">
                                        <div
                                            class="p-2 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">
                                                {{ $data['title'] }}
                                            </p>
                                            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                                {{ $data['count'] }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        You're logged in!
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
