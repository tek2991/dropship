<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoice Details') }}
            </h2>
        </span>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-success-message />
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <h3>
                            <strong>Log Sheet ID: </strong>{{ $invoice->invoice_no }}
                        </h3>
                        <h3 class="md:text-right">
                            <strong>Created: </strong>{{ $invoice->created_at->format('d/m/Y') }}
                        </h3>
                        <h3>
                            <strong>Destination: </strong>{{ $invoice->logSheet->destination }}
                        </h3>
                        <h3 class="md:text-right">
                            <strong>Log Sheet: </strong>
                            <x-text-link href="{{ route('admin.log-sheets.show', $invoice->logSheet) }}">
                                {{ $invoice->logSheet->log_sheet_no }}
                            </x-text-link>
                        </h3>
                        <h3>
                            <strong>Transporter: </strong>
                            <x-text-link
                                href="{{ route('admin.transporters.show', $invoice->logSheet->transporter) }}">
                                {{ $invoice->logSheet->transporterUser->name }}
                            </x-text-link>
                        </h3>
                        <h3 class="md:text-right">
                            <strong>Vehicle: </strong>
                            <x-text-link href="{{ route('admin.vehicles.show', $invoice->logSheet->vehicle) }}">
                                {{ $invoice->logSheet->vehicle->registration_number }}
                            </x-text-link>
                        </h3>
                        <h3>
                            <strong>Driver: </strong>
                            @if ($invoice->logSheet->driverUser()->exists())
                                <x-text-link
                                    href="{{ route('admin.drivers.show', $invoice->logSheet->driverUser) }}">
                                    {{ $invoice->logSheet->driverUser()->exists() ? $invoice->logSheet->driverUser->name : 'NA' }}
                                </x-text-link>
                            @else
                                NA
                            @endif
                        </h3>
                        <h3 class="md:text-right">
                            <strong>Driver Phone: </strong>
                            {{ $invoice->logSheet->driverUser()->exists() ? $invoice->logSheet->driverUser->phone : 'NA' }}
                        </h3>
                        <h3>
                            <strong>Gross Weight: </strong> {{ $invoice->gross_weight }} Kg
                        </h3>
                        <h3 class="md:text-right">
                            <strong>Packs: </strong> {{ $invoice->no_of_packs }}
                        </h3>
                        <h3>
                            <strong>Client: </strong>
                            <x-text-link href="{{ route('admin.clients.show', $invoice->client) }}">
                                {{ $invoice->clientUser->name }}
                            </x-text-link>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
