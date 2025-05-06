<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Surf-Hube</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow h-screen">
        <div class="p-6 text-xl font-bold border-b">Surf-Hube Admin</div>
        <nav class="mt-6">
            <ul>
                <li><a href="" class="block py-2 px-4 hover:bg-gray-200">🏠 Tableau de bord</a></li>
                <li><a href="" class="block py-2 px-4 hover:bg-gray-200">👥 Utilisateurs</a></li>
                <li><a href="" class="block py-2 px-4 hover:bg-gray-200">🏄‍♂️ Coachs</a></li>
                <li><a href="" class="block py-2 px-4 hover:bg-gray-200">📚 Cours</a></li>
                <li><a href="" class="block py-2 px-4 hover:bg-gray-200">🏄 Surfeurs</a></li>
                <li><a href="" class="block py-2 px-4 hover:bg-gray-200">📅 Réservations</a></li>
                <li><a href="" class="block py-2 px-4 hover:bg-gray-200">⚙️ Paramètres</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Content -->
    <main class="flex-1 p-6">
        @yield('content')
    </main>
</body>
</html>
