<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Staff Piket Panel' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex bg-gray-50">
    <!-- Sidebar Staff Piket -->
    <x-staffpiket-sidebar />

    <!-- Konten Utama -->
    <main class="flex-1 p-6">
        {{ $slot }}
    </main>
</body>
</html>