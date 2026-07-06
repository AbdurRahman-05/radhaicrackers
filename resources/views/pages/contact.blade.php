@extends('layouts.app')

@section('title', 'Contact Us - Radhe Crackers')

@section('content')
<!-- Hero Section -->
<div class=" bg-gray-100 py-16">
    <div class="inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-6xl mx-auto px-4 text-center">
    <h1 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h1>
        <p class="text-xl">Get in touch with Radhe Crackers for all your fireworks needs</p>
    </div>
</div>

<!-- Contact Content -->
<div class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Contact Form Section -->
        <div class="mb-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Send a Message</h2>
                <p class="text-gray-600">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
            </div>
            
            <div class="max-w-2xl mx-auto">
                <form class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" id="name" name="name" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                               placeholder="Your full name">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                               placeholder="your.email@example.com">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea id="message" name="message" rows="7" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                                  placeholder="Tell us how we can help you..."></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" 
                                class=" text-white px-8 py-3 rounded-lg hover:bg-gray-600 transition-colors font-semibold" style="background-color: #1E093B;">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>

<!-- Additional Information -->
        <div class="bg-gray-50 rounded-lg p-8 border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Business Hours</h3>
                    <div class="space-y-2 text-gray-600">
                        <p>9:00 AM - 9:00 PM (All day)</p>
                        
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Quick Support</h3>
                    <p class="text-gray-600 mb-4">Get instant support and place orders directly through WhatsApp</p>
                    <a href="https://wa.me/918807060809" target="_blank" 
                       class="inline-flex items-center bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors">
                        <span class="mr-2">💬</span>
                        Chat on WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Information Section -->
        <div class="mb-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Get In Touch</h2>
                <p class="text-gray-600">Find us at our location or contact us through any of these channels</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Address -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center border border-gray-200">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-gray-800" fill="currentColor" viewBox="0 0 384 512">
                            <path d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Address</h3>
                    <p class="text-gray-600 leading-relaxed">
                        4/273-11/7, Virudhunagar Main Road, Amathur, Virudhunagar District, Tamilnadu-626005
                    </p>
                </div>

                <!-- Phone -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center border border-gray-200">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-gray-800" fill="currentColor" viewBox="0 0 512 512">
                            <path d="M497.39 361.8l-112-48a24 24 0 0 0-28 6.9l-49.6 60.6A370.66 370.66 0 0 1 130.6 204.11l60.6-49.6a23.94 23.94 0 0 0 6.9-28l-48-112A24.16 24.16 0 0 0 122.6.61l-104 24A24 24 0 0 0 0 48c0 256.5 207.9 464 464 464a24 24 0 0 0 23.4-18.6l24-104a24.29 24.29 0 0 0-14.01-27.6z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Phone Number</h3>
                    <p class="text-gray-600">
                        <a href="tel:+918807060809" class="hover:text-orange-600">+91 88070 60809</a><br>
                        <a href="tel:+919751048974" class="hover:text-orange-600">+91 97510 48974</a>
                    </p>
                </div>

                <!-- Email -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center border border-gray-200">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-gray-800" fill="currentColor" viewBox="0 0 512 512">
                            <path d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Email</h3>
                    <p class="text-gray-600">
                        <a href="mailto:radhecrackers@gmail.com" class="hover:text-orange-600">radhecrackers@gmail.com</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Google Maps Section -->
        <div class="mb-16">
            <div class="bg-gray-100 rounded-lg overflow-hidden shadow-lg">
                <iframe 
                    src="https://maps.google.com/maps?q=Radhe%20Crackers%20Amathur%20-%20626005.&amp;t=m&amp;z=10&amp;output=embed&amp;iwloc=near"
                    width="100%" 
                    height="400" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Radhe Crackers Location">
                </iframe>
            </div>
        </div>

    </div>
</div>
@endsection 