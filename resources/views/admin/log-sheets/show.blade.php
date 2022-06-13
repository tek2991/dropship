<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Log Sheet Details') }}
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
                            <strong>Log Sheet ID: </strong>{{ $logSheet->log_sheet_no }}
                        </h3>
                        <h3 class="md:text-right">
                            <strong>Created: </strong>{{ $logSheet->created_at->format('d/m/Y') }}
                        </h3>
                        <h3>
                            <strong>Invoices: </strong>{{ $logSheet->invoices->count() }}
                        </h3>
                        <h3 class="md:text-right">
                            <strong>Total Gross Weight: </strong> {{ $logSheet->invoices->sum('gross_weight') }} Kg
                        </h3>
                        <h3>
                            <strong>Total Packs: </strong> {{ $logSheet->invoices->sum('no_of_packs') }}
                        </h3>
                    </div>

                    <div class="flex flex-col mt-8">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-x-auto border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200 table-auto">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Invoice
                                                </th>
                                                <th scope="col"
                                                    class="truncate px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Client (Payer)
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Gross Weight
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Packs
                                                </th>
                                                <th scope="col" class="relative px-6 py-3">
                                                    <span class="sr-only">View</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($invoices as $invoice)
                                                <tr
                                                    class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $invoice->invoice_no }}
                                                        <br>
                                                        {{ $invoice->created_at->format('d/m/Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $invoice->clientUser->name }}
                                                        <br>
                                                        ID: {{ $invoice->client->client_number }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $invoice->gross_weight }} Kg
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $invoice->no_of_packs }}
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="{{ route('admin.invoices.show', $invoice) }}"
                                                            class="text-indigo-600 hover:text-indigo-900">View</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    {{ $invoices->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
