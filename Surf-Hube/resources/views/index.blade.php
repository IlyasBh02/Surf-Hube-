<?php
$apiKey = "09d065dde2msh7b244d3502d893cp19ad63jsn0c70a41869e3";
$city = "Essaouira";

// Appel à l'API météo (WeatherAPI)
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://weatherapi-com.p.rapidapi.com/current.json?q=Essaouira",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "X-RapidAPI-Key: 09d065dde2msh7b244d3502d893cp19ad63jsn0c70a41869e3",
        "X-RapidAPI-Host: weatherapi-com.p.rapidapi.com"
    ],
]);

$response = curl_exec($curl);

if (curl_errno($curl)) {
    $weatherError = "Erreur curl : " . curl_error($curl);
} else {
    $weatherData = json_decode($response, true);
}

curl_close($curl);

// API MagicSeaweed (optionnelle)
$mswApiKey = "1ee99b30b8df70f6633f1c9f35396f64";
$mswApiUrl = "https://magicseaweed.com/api/$mswApiKey/forecast/?spot_id=381&units=uk";
// $mswData = json_decode(file_get_contents($mswApiUrl), true);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Surf School</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800">

<!-- Hero Section -->
<section class="bg-cover bg-center h-screen text-white" style="background-image: url('essaouira.jpg');">
    <!-- Navbar -->
    <nav class="absolute top-0 left-0 w-full bg-opacity-60 bg-black text-white py-4 z-10">
        <div class="max-w-screen-xl mx-auto flex items-center justify-between px-4">
            <a href="#" class="text-2xl font-bold">Surf-Hube</a>
            <div class="space-x-6">
                <a href="#home" class="hover:text-blue-400">Home</a>
                <a href="{{ route('courses') }}" class="hover:text-blue-400">Cours</a>
                <a href="#contact" class="hover:text-blue-400">Contact</a>
                <a href="#apropos" class="hover:text-blue-400">À propos</a>
                @auth
                    @if(auth()->check())
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-500">Tableau de bord</a>
                        @elseif(auth()->user()->isCoach())
                            <a href="{{ route('coach.coachDashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-500">Tableau de bord</a>
                        @endif
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-500">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-500">Login</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-green-600 text-white rounded-full hover:bg-green-500">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Section de bienvenue avec texte superposé -->
    <div class="h-full w-full flex flex-col justify-center items-center bg-black bg-opacity-50">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">Bienvenue à notre École de Surf</h1>
        <p class="text-xl md:text-2xl">Apprenez à surfer avec les meilleurs instructeurs à Essaouira</p>
    </div>
</section>


<!-- Cours de Surf -->
<section class="py-12 bg-gray-100">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-8">Nos Cours de Surf</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-xl font-semibold mb-4">Débutant</h3>
                <p>Apprenez les bases du surf et attrapez votre première vague.</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-xl font-semibold mb-4">Intermédiaire</h3>
                <p>Perfectionnez votre technique et commencez à manœuvrer sur les vagues.</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-xl font-semibold mb-4">Avancé</h3>
                <p>Améliorez vos compétences et dominez les plus grosses vagues.</p>
            </div>
        </div>
    </div>
</section>

<!-- Instructeurs -->
<section class="py-12">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-8">Nos Instructeurs</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <img src="instructeur1.jpg" alt="Instructeur 1" class="mx-auto rounded-full w-32 h-32 object-cover mb-4">
                <h3 class="text-xl font-semibold">Yassine</h3>
                <p>10 ans d'expérience en enseignement du surf.</p>
            </div>
            <div>
                <img src="instructeur2.jpg" alt="Instructeur 2" class="mx-auto rounded-full w-32 h-32 object-cover mb-4">
                <h3 class="text-xl font-semibold">Fatima</h3>
                <p>Spécialiste des débutants et enfants.</p>
            </div>
            <div>
                <img src="instructeur3.jpg" alt="Instructeur 3" class="mx-auto rounded-full w-32 h-32 object-cover mb-4">
                <h3 class="text-xl font-semibold">Ali</h3>
                <p>Compétiteur international et coach professionnel.</p>
            </div>
        </div>
    </div>
</section>

<!-- Météo -->
<section class="py-12 bg-blue-100">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-6">Météo Actuelle à Essaouira</h2>
        <?php if ($weatherData): ?>
    <!-- afficher la météo -->
        <?php else: ?>
            <p class="text-red-500"><?= $weatherError ?? "Impossible de récupérer les données météo." ?></p>
        <?php endif; ?>
    </div>
</section>

<!-- Google Map -->
<section class="py-12">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-6">Nous Trouver</h2>
        <iframe class="w-full h-96 rounded shadow" src="https://maps.google.com/maps?q=safi&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0"></iframe>
    </div>
</section>

</body>
</html>
