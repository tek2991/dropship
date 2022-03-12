<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoice Details') }}
            </h2>
            <x-button-link href="{{ route('admin.invoices.edit', $invoice) }}">
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
                        <h3>
                            <strong>Invoice: </strong>{{ $invoice->invoice_no }}
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
                            @if ($invoice->logSheet->driverUser !== null)
                                <x-text-link
                                    href="{{ route('admin.drivers.show', $invoice->logSheet->driverUser) }}">
                                    {{ $invoice->logSheet->driverUser->name }}
                                </x-text-link>
                            @else
                                NA
                            @endif
                        </h3>
                        <h3 class="md:text-right">
                            <strong>Driver Phone: </strong>
                            {{ $invoice->logSheet->driverUser != null ? $invoice->logSheet->driverUser->phone : 'NA' }}
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
                        <h3 class="md:text-right">
                            <strong>Status: </strong>
                            @if ($invoice->is_delivered)
                                <span class="text-green-500">Delivered <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 inline mb-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg></span>
                            @else
                                <span class="text-red-500">Pending<svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 inline mb-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg></span>
                            @endif
                        </h3>
                    </div>

                    <div class="my-8 p-8">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight my-6">Images</h2>

                        <div id="main-image-slider" class="splide mb-2 col-md-8 offset-md-2">
                            <div class="splide__track">
                                <ul class="splide__list">
                                    @forelse ($invoice->images as $image)
                                        <li class="splide__slide">
                                            <img src="{{ Storage::url($image->url) }}">
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div id="thumbnail-image-slider" class="splide mt-4">
                            <div class="splide__track">
                                <ul class="splide__list">
                                    @forelse ($invoice->images as $image)
                                        <li class="splide__slide">
                                            <img src="{{ Storage::url($image->url) }}">
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var main = new Splide('#main-image-slider', {
                    type: 'fade',
                    rewind: true,
                    pagination: false,
                    arrows: false,
                    fixedHeight: '550px',
                    autoWidth: true,
                    cover: true,
                    breakpoints: {
                        600: {
                            fixedHeight: '200px',
                        },
                    },
                });
                var thumbnails = new Splide('#thumbnail-image-slider', {
                    fixedWidth: 150,
                    fixedHeight: 90,
                    gap: 10,
                    rewind: true,
                    pagination: false,
                    cover: true,
                    focus: 'center',
                    isNavigation: true,
                    breakpoints: {
                        600: {
                            fixedWidth: 60,
                            fixedHeight: 44,
                        },
                    },
                });
                main.sync(thumbnails);
                main.mount();
                thumbnails.mount();
            });
        </script>
    @endsection
</x-app-layout>
