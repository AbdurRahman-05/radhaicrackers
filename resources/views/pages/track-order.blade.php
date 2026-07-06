@extends('layouts.app')

@section('title', 'Track Order - Radhe Crackers')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Track Your Order</h1>
                <p class="text-lg text-gray-600">Enter your tracking number to check the status of your order</p>
            </div>

            <div class="max-w-md mx-auto">
                <form method="POST" action="{{ route('track-order.track') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Tracking number <span class="text-red-500">(*required)</span>
                        </label>
                        <input type="text" 
                               id="tracking_number" 
                               name="tracking_number" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-gray-500 focus:border-gray-500"
                               placeholder="Enter your tracking number"
                               required
                               value="{{ old('tracking_number', $trackingNumber ?? '') }}">
                    </div>

                    <button type="submit" 
                            class="w-full text-white py-3 px-6 rounded-lg hover:bg-gray-600 transition-colors font-semibold" style="background-color: #1E093B;">
                        Track
                    </button>
                </form>

                @if(isset($trackingNumber))
                    <div class="mt-8 p-6 bg-gray-50 rounded-lg" id="tracking-result">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h3>
                        @if($order)
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Order ID:</span>
                                    <span class="font-medium">{{ $order->tracking_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="text-green-600 font-medium">{{ ucfirst($order->status) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Order Date:</span>
                                    <span class="font-medium">{{ $order->created_at->format('F d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Expected Delivery:</span>
                                    <span class="font-medium">{{ $order->expected_delivery ? $order->expected_delivery->format('F d, Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-red-600 font-medium">No order found for tracking number: {{ $trackingNumber }}</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection