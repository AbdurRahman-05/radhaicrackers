@extends('layouts.app')

@section('title', 'About Us - Radhe Crackers')

@section('content')
<div class="bg-white py-8">
    <div class="max-w-5xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
            <ol class="list-reset flex">
                <li><a href="/" class="hover:underline">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-700">About Us</li>
            </ol>
        </nav>
        <!-- Page Title -->
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">About Us</h1>
<!-- Hero Section -->
        <div class="mb-8">
            <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-2">About Us – Spreading Joy, One Spark at a Time</h2>
            <p class="text-base md:text-lg text-gray-700 mb-2">Welcome to Radhe Crackers, your trusted fireworks destination in Sivakasi, the firecracker capital of India. Founded with a passion for celebration and a commitment to quality, we have grown into one of the most reliable and customer-friendly cracker suppliers in the region.</p>
            <p class="text-base md:text-lg text-gray-700">With years of experience in the fireworks industry, we've built a reputation for offering premium-quality crackers at factory prices, backed by exceptional service and customer satisfaction.</p>
        </div>
        <!-- Main Image -->
        <img src="{{ asset('images/about-us/unnamed-file-1024x576.jpg') }}" alt="About Radhe Crackers" class="w-full rounded-xl shadow-lg mb-8">
        <!-- Who We Are -->
        <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-4">Who We Are</h2>
        <p class="text-base md:text-lg text-gray-700 mb-2">Radhe Crackers isn't just a fireworks store — we are a team of celebration enthusiasts who believe in lighting up lives through our products. From small family events to large-scale festive celebrations, we are proud to be a part of your happiest moments.</p>
        <p class="text-base md:text-lg text-gray-700 mb-8">We are based in Sivakasi, Tamil Nadu, home to India's most renowned firework manufacturers. This direct access to top brands allows us to bring genuine, safe, and high-performing fireworks to customers across the country.</p>
        <!-- Team Image -->
        <img src="{{ asset('images/about-us/New-Project-2025-06-03T225841.737.jpg') }}" alt="Our Team" class="w-full rounded-xl shadow-lg mb-8">
        <!-- What We Offer -->
        <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-6">What We Offer</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-purple-900 text-white rounded-lg p-6 text-center font-semibold">150+ varieties of crackers for all ages and events</div>
            <div class="bg-purple-900 text-white rounded-lg p-6 text-center font-semibold">Custom combo packs for weddings, festivals, birthdays, and corporate events</div>
            <div class="bg-purple-900 text-white rounded-lg p-6 text-center font-semibold">All products tested for safety, quality, and visual performance</div>
            <div class="bg-purple-900 text-white rounded-lg p-6 text-center font-semibold">Bulk order options with special discounts</div>
            <div class="bg-purple-900 text-white rounded-lg p-6 text-center font-semibold">Smooth enquiry-to-order system to comply with regulations</div>
        </div>
        <!-- Contact Info -->
        <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-4">Contact Info</h2>
        <div class="mb-8 text-base md:text-lg text-gray-700">
            <div class="mb-2"><span class="font-bold">Address:</span> 3/180-5, Virudhunagar-Sivakasi main road, G.N. Patti, Amathur - 626005.</div>
            <div class="mb-2"><span class="font-bold">Phone:</span> +91 8807060809 / +91 9751048974</div>
            <div><span class="font-bold">Email:</span> radhecrackers@gmail.com</div>
        </div>
    </div>
</div>
@endsection 