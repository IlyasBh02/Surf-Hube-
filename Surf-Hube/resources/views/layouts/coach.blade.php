<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Coach | Surf-Hube</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        });
    </script>
</head>
<body class="flex bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow h-screen">
        <div class="p-6 text-xl font-bold border-b">Surf-Hube Coach</div>
        <nav class="mt-6">
            <ul>
                <li><a href="{{ route('coach.coachDashboard') }}" class="block py-2 px-4 hover:bg-gray-200">🏠 Tableau de bord</a></li>
                <li><a href="{{ route('coach.cours') }}" class="block py-2 px-4 hover:bg-gray-200">📚 Mes cours</a></li>
                <li><a href="{{ route('coach.ajouter_cours') }}" class="block py-2 px-4 hover:bg-gray-200">➕ Ajouter un cours</a></li>
                <li><a href="{{ route('coach.reservations') }}" class="block py-2 px-4 hover:bg-gray-200">📅 Mes réservations</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Content -->
    <main class="flex-1">
        @yield('content')
    </main>
</body>
</html>
