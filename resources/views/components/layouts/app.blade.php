<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Radhe Crackers - Bringing Joy, Spark by Spark' }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
    <style>
        :root {
            --primary-color: #b37a2c;
            --secondary-color: #1e093b;
            --accent-color: #ffca49;
            --text-dark: #222222;
            --text-light: #666666;
        }
        /* ... (copy styles from main layout) ... */
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    {{ $slot }}
    @livewireScripts
</body>
</html> 