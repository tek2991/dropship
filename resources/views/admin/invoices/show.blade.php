<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoice Details') }}
            </h2>
            <x-button-link href="{{ route('admin.invoices.edit', $invoice) }}" class="bg-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Delete</span>
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
                        <h3 class="md:col-span-2">
                            <strong>Remarks: </strong><i>{{ $invoice->remarks }}</i>
                        </h3>
                        <button type="button"
                            class="w-24 px-6 py-2.5 bg-red-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                            data-bs-toggle="modal" data-bs-target="#exampleModal"> Delete</button>
                    </div>

                    @if ($invoice->images->count() > 0)
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight my-6">Uploaded Images</h2>

                        <div class="flex flex-col mt-8">
                            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                    <div class="shadow overflow-x-auto border-b border-gray-200 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200 table-auto">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="relative px-6 py-3">
                                                        <span class="sr-only">Image</span>
                                                    </th>
                                                    <th scope="col"
                                                        class="truncate px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Created by
                                                    </th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Created at
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach ($invoice->images as $image)
                                                    <tr
                                                        class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            <img src="{{ Storage::url($image->folder . '/' . $image->filename) }}"
                                                                class="h-12 w-12 cursor-pointer max-w-none"
                                                                data-fancybox="gallery">
                                                            </img>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {{ $image->createdBy->name }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {{ $image->created_at->format('d/m/Y') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight my-6">No Uploaded Images</h2>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog relative w-auto pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div
                    class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                    <h5 class="text-xl font-medium leading-normal text-gray-800" id="exampleModalLabel">Confirm</h5>
                    <button type="button"
                        class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body relative p-4"> Are you sure you want to delete this invoice? </div>
                <div
                    class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
                    <button type="button"
                        class="px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                        data-bs-dismiss="modal">Close
                    </button>
                    <form method="POST" action="{{ route('admin.invoices.destroy', $invoice) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-6 py-2.5 bg-red-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-800 active:shadow-lg transition duration-150 ease-in-out ml-1">Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
