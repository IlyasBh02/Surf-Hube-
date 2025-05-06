<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin | Surf-Hube</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100 min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow h-screen">
        <div class="p-6 text-xl font-bold border-b">Surf-Hube Admin</div>
        <nav class="mt-6">
            <ul>
                <li><a href="" class="block py-2 px-4 hover:bg-gray-200">🏠 Dashboard</a></li>
                <li><a href="" class="block py-2 px-4 hover:bg-gray-200">👥 Utilisateurs</a></li>
                <li><a href="" class="block py-2 px-4 hover:bg-gray-200">👨‍🏫 Gérer les coachs</a></li>
                <li><a href="" class="block py-2 px-4 hover:bg-gray-200">📚 Gérer les cours</a></li>
                <li><a href="" class="block py-2 px-4 hover:bg-gray-200">🏄‍♂️ Gérer les surfeurs</a></li>
                <li><a href="" class="block py-2 px-4 hover:bg-gray-200">📅 Réservations</a></li>
                <li><a href="" class="block py-2 px-4 hover:bg-gray-200">⚙️ Paramètres</a></li>
            </ul>
        </nav>
    </aside>
    <!-- Content -->
    <main class="flex-1 p-6">
       {{ $slot }}
    </main>
</body>
</html>
