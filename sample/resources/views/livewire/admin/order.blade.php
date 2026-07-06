@extends('layouts.app')

@section('title', 'Order Now - Cracker Shop')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Order Your Crackers</h1>
            <p class="text-lg text-gray-600">Select your favorite fireworks and place your order</p>
        </div>

        <livewire:pages.order-now />
    </div>
</div>
@endsection 