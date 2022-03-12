<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Update Invoice') }}
            </h2>
            <x-button-link href="{{ route('admin.invoices.show', $invoice) }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0019 16V8a1 1 0 00-1.6-.8l-5.333 4zM4.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0011 16V8a1 1 0 00-1.6-.8l-5.334 4z" />
                </svg>
                <span>Back</span>
            </x-button-link>
        </span>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-success-message />
                    <x-auth-validation-errors />

                    <form method="POST" action="{{ route('admin.invoices.update', $invoice) }}">
                        @method('PUT')
                        @csrf
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-label for="is_delivered" :value="__('Status')" />
                                <x-input-select id="is_delivered" class="block mt-1 w-full" name="is_delivered" required>
                                    <option value="1" {{ $invoice->is_delivered == true ? 'selected' : '' }}>Delivered
                                    </option>
                                    <option value="0" {{ $invoice->is_delivered != true ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                </x-input-select>
                            </div>
                            <div>
                                <x-label for="image" :value="__('Select Images')" />
                                <x-label for="image" class="text-xs text-red-800"
                                    :value="__('Accepted formats: xls, xlsx, csv, ods')" />
                                <x-label for="image" class="text-xs text-red-800" :value="__('Max size: 2MB')" />
                                <x-input id="image" class="block mt-1 w-full" type="file" name="image[]" multiple />
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4 ">
                            <x-button class="ml-3" type="submit" id="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Update') }}
                            </x-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // get a reference to the fieldset element
                const fieldsetElement = document.querySelector("input[id='image']");

                //  get a reference to the submit button
                const submitButton = document.querySelector("button[id='submit']");

                // create a FilePond instance at the fieldset element location
                const pond = FilePond.create(fieldsetElement);


                // configure FilePond
                let serverREsponse = null;
                pond.setOptions({
                    labelIdle: '{!! __('Drag and drop or <span class="text-blue-500">browse</span>') !!}',
                    allowMultiple: true,
                    imagePreviewMinHeight: '100px',
                    imagePreviewMaxHeight: '200px',
                    maxFiles: 5,
                    acceptedFileTypes: ['image/*'],
                    maxFileSize: '2MB',
                    server: {
                        process: {
                            url: '{{ route('admin.uploads.store') }}',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            onerror: (response) => {
                                serverResponse = JSON.parse(response);
                                pond.setOptions({
                                    labelError: serverResponse.message
                                });
                            }
                        },
                        revert: {
                            url: '{{ route('admin.uploads.destroy') }}',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                '_method': 'DELETE'
                            }
                        }
                    },

                    labelFileProcessingError: () => {
                        // replaces the error on the FilePond error label
                        return serverResponse.message;
                    },

                    onaddfilestart: function(file) {
                        // disable submit button while file is uploading
                        submitButton.setAttribute('disabled', 'true');
                    },

                    onprocessfiles: () => {
                        // enable submit button when file is uploaded
                        submitButton.removeAttribute('disabled');
                    },
                    onupdatefiles(files) {
                        // disable submit button if no files are selected
                        const count_files = files.length;
                        count_files > 0 ? submitButton.removeAttribute('disabled') : submitButton.setAttribute(
                            'disabled', 'true');
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>
