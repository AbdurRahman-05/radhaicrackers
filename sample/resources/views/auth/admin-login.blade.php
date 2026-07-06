<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Radhe Crackers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 rounded-full flex items-center justify-center mb-4" style="background-color: #1E093B">
                    <i class="fas fa-fire text-white text-2xl" ></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Admin Login</h2>
                <p class="mt-2 text-sm text-gray-600">Radhe Crackers Management</p>
            </div>

            <!-- Login Form -->
            <form class="space-y-6" method="POST" action="{{ route('admin.login') }}">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email Address
                    </label>
                    <div class="mt-1 relative">
                        <input id="email" name="email" type="email" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-gray-500 focus:border-gray-500 focus:z-10 sm:text-sm @error('email') border-gray-500 @enderror"
                               placeholder="admin@radhecrackers.com"
                               value="{{ old('email') }}">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-envelope text-gray-800"></i>
                        </div>
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-gray-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-gray-900 focus:border-gray-900 focus:z-10 sm:text-sm @error('password') border-gray-900 @enderror"
                               placeholder="Enter your password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-lock text-gray-800"></i>
                        </div>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-gray-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                               class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out" style="background-color: #1E093B">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-white group-hover:text-white"></i>
                        </span>
                        Sign in to Admin Panel
                    </button>
                </div>
            </form>

            <!-- Default Admin Credentials (for demo/testing) -->
            <div class="mt-6">
                <div class="bg-gray-50 border border-dashed border-gray-300 rounded p-4 text-center">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <span class="text-sm font-semibold text-gray-700">Default Admin Login (Demo):</span>
                    </div>
                    <div class="text-sm text-gray-700">
                        <span class="font-mono">admin@radhecrackers.com</span> / <span class="font-mono">admin123</span>
                    </div>
                    <div class="text-xs text-gray-400 mt-1">Change these credentials after first login.</div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-sm text-gray-800 hover:text-red-500">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back to Website
                </a>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="text-center">
            <p class="text-xs text-gray-500">
                <i class="fas fa-shield-alt mr-1"></i>
                Secure admin access only
            </p>
        </div>
    </div>

    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-gray-500', 'ring-opacity-50');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-gray-500', 'ring-opacity-50');
                });
            });
        });
    </script>
</body>
</html> 