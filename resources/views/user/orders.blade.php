@extends('layouts.app')

@section('title', 'My Orders - Cracker Shop')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
            <p class="text-gray-600">Track all your orders and their status</p>
        </div>

        @livewire('user.orders')
    </div>
</div>
@endsection 