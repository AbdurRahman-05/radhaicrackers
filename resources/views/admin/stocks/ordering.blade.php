@extends('layouts.admin')

@section('title', 'Stock Ordering')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Stock Ordering</h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm">
        @livewire('admin.stock-ordering')
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
@endpush
@endsection
