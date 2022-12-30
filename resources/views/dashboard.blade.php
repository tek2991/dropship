<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if (auth()->user()->hasRole('admin') ||
        auth()->user()->hasRole('manager'))
        @php
            $is_admin = auth()
                ->user()
                ->hasRole('admin');
            $is_manager = auth()
                ->user()
                ->hasRole('manager');
            
            if ($is_manager) {
                $location_ids_if_manager = auth()
                    ->user()
                    ->manager->locations->pluck('id')
                    ->toArray();
            
                $driver_ids = array_unique(
                    \App\Models\Location::whereIn('id', $location_ids_if_manager)
                        ->with('drivers')
                        ->get()
                        ->pluck('drivers')
                        ->flatten()
                        ->pluck('id')
                        ->toArray(),
                );
                $client_ids = array_unique(
                    \App\Models\Location::whereIn('id', $location_ids_if_manager)
                        ->with('clients')
                        ->get()
                        ->pluck('clients')
                        ->flatten()
                        ->pluck('id')
                        ->toArray(),
                );
                $vehicle_ids = array_unique(
                    \App\Models\Location::whereIn('id', $location_ids_if_manager)
                        ->with('vehicles')
                        ->get()
                        ->pluck('vehicles')
                        ->flatten()
                        ->pluck('id')
                        ->toArray(),
                );
                $transporter_ids = array_unique(
                    \App\Models\Location::whereIn('id', $location_ids_if_manager)
                        ->with('transporters')
                        ->get()
                        ->pluck('transporters')
                        ->flatten()
                        ->pluck('id')
                        ->toArray(),
                );
            
                $import_ids = \App\Models\Import::whereIn('location_id', $location_ids_if_manager)
                    ->pluck('id')
                    ->toArray();
                $log_sheet_ids = \App\Models\LogSheet::whereIn('location_id', $location_ids_if_manager)
                    ->pluck('id')
                    ->toArray();
                $invoice_ids = \App\Models\Invoice::whereIn('location_id', $location_ids_if_manager)
                    ->pluck('id')
                    ->toArray();
            
                $delivered_invoice_ids = \App\Models\Invoice::whereIn('location_id', $location_ids_if_manager)
                    ->where('delivery_status', 'delivered')
                    ->pluck('id')
                    ->toArray();
                $pending_invoice_ids = \App\Models\Invoice::whereIn('location_id', $location_ids_if_manager)
                    ->where('delivery_status', 'pending')
                    ->pluck('id')
                    ->toArray();
                $cancelled_invoice_ids = \App\Models\Invoice::whereIn('location_id', $location_ids_if_manager)
                    ->where('delivery_status', 'cancelled')
                    ->pluck('id')
                    ->toArray();
            }
            
            $counts = [
                'drivers' => [
                    'title' => 'Drivers',
                    'count' => $is_manager ? count($driver_ids) : \App\Models\Driver::count(),
                    'url' => route('admin.drivers.index'),
                    'data' => null,
                    'color' => 'bg-gray-100',
                ],
                'clients' => [
                    'title' => 'Clients',
                    'count' => $is_manager ? count($client_ids) : \App\Models\Client::count(),
                    'url' => route('admin.clients.index'),
                    'data' => null,
                    'color' => 'bg-gray-100',
                ],
                'vehicles' => [
                    'title' => 'Vehicles',
                    'count' => $is_manager ? count($vehicle_ids) : \App\Models\Vehicle::count(),
                    'url' => route('admin.vehicles.index'),
                    'data' => null,
                    'color' => 'bg-gray-100',
                ],
                'transporters' => [
                    'title' => 'Transporters',
                    'count' => $is_manager ? count($transporter_ids) : \App\Models\Transporter::count(),
                    'url' => route('admin.transporters.index'),
                    'data' => null,
                    'color' => 'bg-gray-100',
                ],
                'imports' => [
                    'title' => 'Imports',
                    'count' => $is_manager ? count($import_ids) : \App\Models\Import::count(),
                    'url' => route('admin.imports.index'),
                    'data' => null,
                    'color' => 'bg-gray-100',
                ],
                'log_sheets' => [
                    'title' => 'Log Sheets',
                    'count' => $is_manager ? count($log_sheet_ids) : \App\Models\LogSheet::count(),
                    'url' => route('admin.log-sheets.index'),
                    'data' => null,
                    'color' => 'bg-gray-100',
                ],
                'total_invoices' => [
                    'title' => 'Total Invoices',
                    'count' => $is_manager ? count($invoice_ids) : \App\Models\Invoice::count(),
                    'url' => route('admin.invoices.index'),
                    'data' => null,
                    'color' => 'bg-gray-100',
                ],
                'delivered_invoices' => [
                    'title' => 'Delivered Invoices',
                    'count' => $is_manager ? count($delivered_invoice_ids) : \App\Models\Invoice::where('delivery_status', 'delivered')->count(),
                    'url' => route('admin.invoices.delivered'),
                    'data' => null,
                    'color' => 'bg-green-100 ',
                ],
                'pending_invoices' => [
                    'title' => 'Pending Invoices',
                    'count' => $is_manager ? count($pending_invoice_ids) : \App\Models\Invoice::where('delivery_status', 'pending')->count(),
                    'url' => route('admin.invoices.pending'),
                    'data' => null,
                    'color' => 'bg-yellow-100 ',
                ],
                'cancelled_invoices' => [
                    'title' => 'Cancelled Invoices',
                    'count' => $is_manager ? count($cancelled_invoice_ids) : \App\Models\Invoice::where('delivery_status', 'cancelled')->count(),
                    'url' => route('admin.invoices.cancelled'),
                    'data' => null,
                    'color' => 'bg-orange-100 ',
                ],
            ];
            
            $pending_invoices_count = [
                [
                    'title' => 'One Day',
                    'count' => $is_manager
                        ? \App\Models\Invoice::whereIn('id', $pending_invoice_ids)
                            ->where('date', Carbon::now()->subDays(1)->toDateString())
                            ->count()
                        : \App\Models\Invoice::where('date', Carbon::now()->subDays(1)->toDateString())->count(),
                    'url' => route('admin.invoices.pending', ['days' => '1', 'title' => 'One Day']),
                    'data' => null,
                    'color' => 'bg-yellow-100 ',
                ],
                [
                    'title' => 'Two Days',
                    'count' => $is_manager
                        ? \App\Models\Invoice::whereIn('id', $pending_invoice_ids)
                            ->where('date', Carbon::now()->subDays(2)->toDateString())
                            ->count()
                        : \App\Models\Invoice::where('date', Carbon::now()->subDays(2)->toDateString())->count(),
                    'url' => route('admin.invoices.pending', ['days' => '2', 'title' => 'Two Days']),
                    'data' => null,
                    'color' => 'bg-yellow-100 ',
                ],
                [
                    'title' => 'Three Days',
                    'count' => $is_manager
                        ? \App\Models\Invoice::whereIn('id', $pending_invoice_ids)
                            ->where('date', Carbon::now()->subDays(3)->toDateString())
                            ->count()
                        : \App\Models\Invoice::where('date', Carbon::now()->subDays(3)->toDateString())->count(),
                    'url' => route('admin.invoices.pending', ['days' => '3', 'title' => 'Three Days']),
                    'data' => null,
                    'color' => 'bg-yellow-100 ',
                ],
                [
                    'title' => 'Four Days',
                    'count' => $is_manager
                        ? \App\Models\Invoice::whereIn('id', $pending_invoice_ids)
                            ->where('date', Carbon::now()->subDays(4)->toDateString())
                            ->count()
                        : \App\Models\Invoice::where('date', Carbon::now()->subDays(4)->toDateString())->count(),
                    'url' => route('admin.invoices.pending', ['days' => '4', 'title' => 'Four Days']),
                    'data' => null,
                    'color' => 'bg-yellow-100 ',
                ],
                [
                    'title' => 'Five Days',
                    'count' => $is_manager
                        ? \App\Models\Invoice::whereIn('id', $pending_invoice_ids)
                            ->where('date', Carbon::now()->subDays(5)->toDateString())
                            ->count()
                        : \App\Models\Invoice::where('date', Carbon::now()->subDays(5)->toDateString())->count(),
                    'url' => route('admin.invoices.pending', ['days' => '5', 'title' => 'Five Days']),
                    'data' => null,
                    'color' => 'bg-yellow-100 ',
                ],
                [
                    'title' => 'Six Days',
                    'count' => $is_manager
                        ? \App\Models\Invoice::whereIn('id', $pending_invoice_ids)
                            ->where('date', Carbon::now()->subDays(6)->toDateString())
                            ->count()
                        : \App\Models\Invoice::where('date', Carbon::now()->subDays(6)->toDateString())->count(),
                    'url' => route('admin.invoices.pending', ['days' => '6', 'title' => 'Six Days']),
                    'data' => null,
                    'color' => 'bg-yellow-100 ',
                ],
                [
                    'title' => 'One week',
                    'count' => $is_manager
                        ? \App\Models\Invoice::whereIn('id', $pending_invoice_ids)
                            ->whereBetween('date', [Carbon::now()->subDays(13)->toDateString(), Carbon::now()->subDays(7)->toDateString()])
                            ->count()
                        : \App\Models\Invoice::where('date', Carbon::now()->subDays(7)->toDateString())->count(),
                    'url' => route('admin.invoices.pending', ['days' => '7', 'days2' => '13', 'title' => 'One Week']),
                    'data' => null,
                    'color' => 'bg-yellow-100 ',
                ],
                [
                    'title' => 'Two Weeks & More',
                    'count' => $is_manager
                        ? \App\Models\Invoice::whereIn('id', $pending_invoice_ids)
                            ->where('date', '<=', Carbon::now()->subDays(14)->toDateString())
                            ->count()
                        : \App\Models\Invoice::where('date', '<', Carbon::now()->subDays(14)->toDateString())->count(),
                    'url' => route('admin.invoices.pending', ['days' => '14', 'title' => 'Two Weeks & More']),
                    'data' => null,
                    'color' => 'bg-yellow-100 ',
                ],
            ];
        @endphp
    @endif

    @if (auth()->user()->hasRole('admin') ||
        auth()->user()->hasRole('manager'))
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($counts as $item => $data)
                                <a href="{{ $data['url'] }}" class="{{ $data['data'] ? 'col-span-2' : '' }}">
                                    <div
                                        class="flex items-center p-4 {{ $data['color'] }} rounded-lg shadow-xs dark:bg-gray-800">
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
                    </div>
                </div>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            <h3 class="md:col-span-3 lg:col-span-4 text-lg">
                                Pendency Details
                            </h3>
                            @foreach ($pending_invoices_count as $item)
                                <a href="{{ $item['url'] }}" class="{{ $item['data'] ? 'col-span-2' : '' }}">
                                    <div
                                        class="flex items-center p-4 {{ $item['color'] }} rounded-lg shadow-xs dark:bg-gray-800">
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
                                                {{ $item['title'] }}
                                            </p>
                                            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                                {{ $item['count'] }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        @livewire('pending-invoice-table')
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        You're logged in!
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
