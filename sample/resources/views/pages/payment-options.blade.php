@extends('layouts.app')

@section('title', 'Payment Options - Radhe Crackers')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-orange-500 to-red-600 text-white py-16">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-6xl mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">Payment Options</h1>
        <p class="text-xl">Secure and convenient payment methods for your orders</p>
    </div>
</div>

<!-- Payment Options Content -->
<div class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">

        <!-- UPI Payment Section -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <div class="text-center mb-8">
                <div class="text-6xl mb-4">💳</div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">UPI Payment</h2>
                <p class="text-lg text-gray-600">Fast, secure, and convenient payment through UPI</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- How it works -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">How it Works</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-orange-600 font-bold">1</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Place Your Order</h4>
                                <p class="text-gray-600">Select your products and complete the order form</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-orange-600 font-bold">2</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Make Payment</h4>
                                <p class="text-gray-600">Pay using any UPI app (GPay, PhonePe, Paytm, etc.)</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-orange-600 font-bold">3</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Provide Transaction Details</h4>
                                <p class="text-gray-600">Share UPI ID and transaction ID for verification</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UPI Details -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">UPI Payment Details</h3>
                    
                    <div class="space-y-4">
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-2">UPI ID</h4>
                            <p class="text-lg font-mono text-orange-600">crackershop@upi</p>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-2">Account Name</h4>
                            <p class="text-lg">Cracker Shop</p>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-2">Bank</h4>
                            <p class="text-lg">State Bank of India</p>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-medium text-blue-900 mb-2">Important Notes:</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Please include your order number in the payment description</li>
                            <li>• Keep the transaction receipt for verification</li>
                            <li>• Payment verification takes 1-2 hours</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supported UPI Apps -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-center mb-8">Supported UPI Apps</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-2xl">📱</span>
                    </div>
                    <h3 class="font-semibold text-gray-900">Google Pay</h3>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-2xl">📱</span>
                    </div>
                    <h3 class="font-semibold text-gray-900">PhonePe</h3>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-2xl">📱</span>
                    </div>
                    <h3 class="font-semibold text-gray-900">Paytm</h3>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-2xl">📱</span>
                    </div>
                    <h3 class="font-semibold text-gray-900">BHIM</h3>
                </div>
            </div>
        </div>

        <!-- Security & Benefits -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center mb-4">
                    <div class="text-4xl mb-2">🔒</div>
                    <h3 class="text-xl font-semibold text-gray-900">Security Features</h3>
                </div>
                <ul class="space-y-2 text-gray-600">
                    <li>• End-to-end encryption</li>
                    <li>• Secure payment gateway</li>
                    <li>• Transaction verification</li>
                    <li>• No card details stored</li>
                    <li>• Instant payment confirmation</li>
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center mb-4">
                    <div class="text-4xl mb-2">✨</div>
                    <h3 class="text-xl font-semibold text-gray-900">Benefits</h3>
                </div>
                <ul class="space-y-2 text-gray-600">
                    <li>• Instant payment processing</li>
                    <li>• No additional charges</li>
                    <li>• 24/7 availability</li>
                    <li>• Easy to use</li>
                    <li>• Widely accepted</li>
                </ul>
            </div>
        </div>

        <!-- Contact Support -->
        <div class="mt-8 bg-orange-50 rounded-lg p-6 text-center">
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Need Help with Payment?</h3>
            <p class="text-gray-600 mb-4">Our support team is here to help you with any payment-related questions</p>
            <a href="https://wa.me/919876543210" target="_blank" 
               class="inline-flex items-center bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors">
                <span class="mr-2">💬</span>
                WhatsApp Support
            </a>
        </div>
    </div>
</div>
@endsection 