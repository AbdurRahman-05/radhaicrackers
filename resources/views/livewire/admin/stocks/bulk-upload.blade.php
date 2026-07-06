<div class="space-y-6">
    <!-- Upload Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Bulk Upload Stocks</h3>
            <div class="flex space-x-2">
                <button wire:click="downloadTemplate('csv')" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    CSV Template
                </button>
                <button wire:click="downloadTemplate('xlsx')" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Excel Template
                </button>
            </div>
        </div>

        <!-- File Upload -->
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Choose File (CSV, Excel)
                </label>
                <div class="flex items-center justify-center w-full">
                    <label for="file-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors {{ $uploadFile ? 'border-green-400 bg-green-50' : '' }}">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            @if($uploadFile)
                                <svg class="w-8 h-8 mb-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-green-600 font-medium">{{ $fileInfo['name'] ?? 'File selected' }}</p>
                                @if(isset($fileInfo['size']))
                                    <p class="text-xs text-gray-500">{{ $fileInfo['size'] }} • {{ $fileInfo['type'] }}</p>
                                @endif
                            @else
                                <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500">
                                    <span class="font-semibold">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-xs text-gray-400">CSV, Excel files (MAX. 10MB)</p>
                            @endif
                        </div>
                        <input id="file-upload" wire:model="uploadFile" type="file" class="hidden" accept=".csv,.txt,.xlsx,.xls">
                    </label>
                </div>
                @error('uploadFile') 
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Progress Bar -->
            @if($isUploading && $uploadProgress > 0)
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $uploadProgress }}%"></div>
                </div>
                <p class="text-sm text-gray-600 text-center">Processing... {{ $uploadProgress }}%</p>
            @endif
        </div>
    </div>

    <!-- Preview Section -->
    @if($showPreview && !empty($previewData))
        <div class="bg-white rounded-lg shadow-md p-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">File Preview</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if(isset($previewData[0]))
                                @foreach($previewData[0] as $index => $header)
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ is_string($header) ? $header : 'Column ' . ($index + 1) }}
                                    </th>
                                @endforeach
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach(array_slice($previewData, 1, 4) as $row)
                            <tr>
                                @foreach($row as $cell)
                                    <td class="px-4 py-2 text-sm text-gray-900 max-w-xs truncate">
                                        {{ $cell }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="mt-2 text-sm text-gray-600">Showing first 4 rows of data</p>
            
            <!-- Import Button -->
            <div class="mt-6 flex justify-end space-x-3">
                <button wire:click="resetUpload" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    Cancel
                </button>
                <button wire:click="import" 
                        wire:loading.attr="disabled"
                        class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="import">Import Data</span>
                    <span wire:loading wire:target="import">Importing...</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Messages Section -->
    @if($successMessage || $errorMessage || $warningMessage)
        <div class="space-y-4">
            @if($successMessage)
                <div class="bg-green-50 border border-green-200 rounded-md p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ $successMessage }}</p>
                            @if($importedCount > 0)
                                <p class="text-sm text-green-600 mt-1">{{ $importedCount }} items successfully imported.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($warningMessage)
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-800">{{ $warningMessage }}</p>
                            @if($skippedCount > 0)
                                <p class="text-sm text-yellow-600 mt-1">{{ $skippedCount }} rows were skipped.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($errorMessage)
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ $errorMessage }}</p>
                            @if($errorCount > 0)
                                <p class="text-sm text-red-600 mt-1">{{ $errorCount }} rows failed to import.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
        <h4 class="text-sm font-semibold text-blue-900 mb-2">Upload Instructions:</h4>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• Download the template file first to see the correct format</li>
            <li>• Required fields: item_name, category, price</li>
            <li>• Optional fields: quantity, description, discounts, etc.</li>
            <li>• For items with quotes like '4"" Gold Lakshmi', the quotes will be preserved</li>
            <li>• Maximum file size: 10MB</li>
            <li>• Supported formats: CSV (.csv, .txt) and Excel (.xlsx, .xls)</li>
        </ul>
    </div>
</div>

<script>
    // File drag and drop functionality
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.querySelector('[for="file-upload"]');
        
        if (uploadArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                uploadArea.classList.add('border-blue-400', 'bg-blue-50');
            }

            function unhighlight(e) {
                uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
            }
        }
    });
</script>
