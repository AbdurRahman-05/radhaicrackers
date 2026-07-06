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
                <form method="POST" action="#" class="space-y-6">
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
                               required>
                    </div>

                    <button type="submit" 
                            class="w-full text-white py-3 px-6 rounded-lg hover:bg-gray-600 transition-colors font-semibold" style="background-color: #1E093B;">
                        Track
                    </button>
                </form>

                <!-- Sample tracking result (hidden by default) -->
                <div class="mt-8 p-6 bg-gray-50 rounded-lg hidden" id="tracking-result">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order ID:</span>
                            <span class="font-medium">#ORD-2025-001</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="text-green-600 font-medium">Confirmed</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Date:</span>
                            <span class="font-medium">January 15, 2025</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Expected Delivery:</span>
                            <span class="font-medium">January 18, 2025</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const trackingResult = document.getElementById('tracking-result');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const trackingNumber = document.getElementById('tracking_number').value;
        
        if (trackingNumber.trim()) {
            // Show tracking result (in real app, this would make an AJAX call)
            trackingResult.classList.remove('hidden');
            trackingResult.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
</script>
@endsection 