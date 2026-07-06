@extends('layouts.admin')

@section('title', 'Stock Management')
@section('page-title', 'Stock Management')

@section('content')
    <div class="mb-4 flex justify-end">
        <button onclick="window.livewire.emit('exportOrderedItems')" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export Ordered Items
        </button>
    </div>
    @livewire('admin.stocks')
@endsection