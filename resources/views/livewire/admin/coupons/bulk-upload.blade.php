<div>
    <input type="file" wire:model="csv_file" accept=".csv" id="couponCsvInput" class="hidden" />
    <button type="button" onclick="document.getElementById('couponCsvInput').click()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
        </svg>
        Bulk Upload
    </button>
    <button type="button" wire:click="upload" class="hidden" id="hiddenCouponUploadBtn"></button>
    <script>
        document.getElementById('couponCsvInput').addEventListener('change', function() {
            document.getElementById('hiddenCouponUploadBtn').click();
        });
    </script>
    @if ($successMessage)
        <div class="mt-2 text-green-600">{{ $successMessage }}</div>
    @endif
    @if ($errorMessage)
        <div class="mt-2 text-red-600">{{ $errorMessage }}</div>
    @endif
</div> 