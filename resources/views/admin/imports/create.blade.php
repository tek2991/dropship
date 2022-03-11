<x-app-layout>
    <x-slot name="header">
        <span class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Import Data') }}
            </h2>
        </span>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-validation-errors />

                    <form method="POST" action="{{ route('admin.imports.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-label for="file" :value="__('Select Data File')" />
                                <x-label for="file" class="text-xs text-red-800"
                                    :value="__('Accepted formats: xls, xlsx, csv, ods')" />
                                <x-label for="file" class="text-xs text-red-800" :value="__('Max size: 2MB')" />
                                <x-input id="file" class="block mt-1 w-full" type="file" name="file" required />
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4 ">
                            <x-button class="ml-3" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                {{ __('Import') }}
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
                const fieldsetElement = document.querySelector("input[id='file']");

                // create a FilePond instance at the fieldset element location
                const pond = FilePond.create(fieldsetElement);

                // configure FilePond
                let serverREsponse = null;
                pond.setOptions({
                    labelIdle: '{!! __('Drag and drop or <span class="text-blue-500">browse</span>') !!}',
                    // acceptedFileTypes: ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'],
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
                });
            });
        </script>
    @endsection
</x-app-layout>
