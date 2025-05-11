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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surf-Hube</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- SwiperJS CDN -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap');
        body {
            font-family: 'Montserrat', sans-serif;
        }
        .hero-image {
            background-image: url('https://images.unsplash.com/photo-1502680390469-be75c86b636f?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
        }
        .gradient-overlay {
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.5));
        }
        .surf-card {
            transition: all 0.3s ease;
        }
        .surf-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        .coach-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 3px solid #3B82F6;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<!-- Hero Section avec Menu -->
<section class="relative h-screen">
    <!-- Image de fond avec overlay gradient -->
    <div class="absolute inset-0 hero-image">
        <div class="absolute inset-0 gradient-overlay"></div>
    </div>
    
    <!-- Navbar -->
    <nav class="absolute top-0 left-0 w-full bg-black bg-opacity-40 text-white py-4 z-10">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-4">
            <a href="#" class="text-3xl font-bold text-white">
                <span class="text-blue-400">Surf</span>-Hube
            </a>
            <div class="hidden md:flex space-x-8">
                <a href="#home" class="font-medium hover:text-blue-400 transition">Accueil</a>
                <a href="#courses" class="font-medium hover:text-blue-400 transition">Cours</a>
                <a href="#coaches" class="font-medium hover:text-blue-400 transition">Coaches</a>
                <a href="#contact" class="font-medium hover:text-blue-400 transition">Contact</a>
                <a href="#about" class="font-medium hover:text-blue-400 transition">À propos</a>
                
                <!-- Boutons Conditionnels basés sur l'authentification -->
                @auth
                    @if(auth()->check())
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-500 transition">Dashboard</a>
                        @elseif(auth()->user()->isCoach())
                            <a href="{{ route('coach.coachDashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-500 transition">Dashboard</a>
                        @endif
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-500 transition">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-500 transition">Connexion</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-green-600 text-white rounded-full hover:bg-green-500 transition">Inscription</a>
                @endauth
            </div>
            
            <!-- Menu Mobile -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="p-2">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Menu Mobile Dropdown -->
        <div id="mobile-menu" class="hidden md:hidden bg-black bg-opacity-90 px-4 py-2">
            <a href="#home" class="block py-2 hover:text-blue-400">Accueil</a>
            <a href="#courses" class="block py-2 hover:text-blue-400">Cours</a>
            <a href="#coaches" class="block py-2 hover:text-blue-400">Coaches</a>
            <a href="#contact" class="block py-2 hover:text-blue-400">Contact</a>
            <a href="#about" class="block py-2 hover:text-blue-400">À propos</a>
            
            @auth
                <hr class="my-2 border-gray-600">
                @if(auth()->check() && auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block py-2 text-blue-400">Dashboard</a>
                @elseif(auth()->check() && auth()->user()->isCoach())
                    <a href="{{ route('coach.coachDashboard') }}" class="block py-2 text-blue-400">Dashboard</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left py-2 text-red-400">Déconnexion</button>
                </form>
            @else
                <hr class="my-2 border-gray-600">
                <a href="{{ route('login') }}" class="block py-2 text-blue-400">Connexion</a>
                <a href="{{ route('register') }}" class="block py-2 text-green-400">Inscription</a>
            @endauth
        </div>
    </nav>

    <!-- Contenu Hero -->
    <div class="relative h-full flex flex-col justify-center items-center text-center px-4 text-white">
        <h1 class="text-5xl md:text-7xl font-bold mb-6">Découvrez le <span class="text-blue-400">Surf</span>-Hube</h1>
        <p class="text-xl md:text-2xl max-w-2xl mb-10">Rejoignez notre école de surf à Essaouira pour une expérience inoubliable avec les meilleurs instructeurs du Maroc</p>
        <div class="flex flex-wrap justify-center gap-4">
            <!-- <a href="#courses" class="px-8 py-3 bg-blue-600 text-white rounded-full hover:bg-blue-500 transition font-bold">Nos Cours</a> -->
            <!-- <a href="#contact" class="px-8 py-3 bg-white text-blue-600 rounded-full hover:bg-gray-100 transition font-bold">Nous Contacter</a> -->
        </div>
    </div>
</section>

<!-- Cours de Surf -->
<section id="courses" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">Nos Cours de <span class="text-blue-600">Surf</span></h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Découvrez nos différentes formules adaptées à tous les niveaux, de débutant à expert</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="surf-card bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-48 bg-blue-100 relative">
                    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e" alt="Cours débutant" class="w-full h-full object-cover">
                    <div class="absolute top-0 right-0 bg-green-500 text-white px-4 py-1 rounded-bl-lg font-bold">Débutant</div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-3">Initiation au Surf</h3>
                    <p class="text-gray-600 mb-4">Apprenez les bases du surf et attrapez votre première vague dans une ambiance détendue et sécurisée.</p>
                    <ul class="mb-6 space-y-2">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Équipement fourni</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Instructeur dédié</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Théorie et pratique</li>
                    </ul>
                    <div class="text-center">
                        <a href="#contact" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-500 transition">Réserver</a>
                    </div>
                </div>
            </div>
            
            <div class="surf-card bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-48 bg-blue-100 relative">
                    <img src="https://images.unsplash.com/photo-1455729552865-3658a5d39692" alt="Cours intermédiaire" class="w-full h-full object-cover">
                    <div class="absolute top-0 right-0 bg-blue-500 text-white px-4 py-1 rounded-bl-lg font-bold">Intermédiaire</div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-3">Perfectionnement</h3>
                    <p class="text-gray-600 mb-4">Améliorez votre technique et commencez à manœuvrer sur les vagues avec confiance.</p>
                    <ul class="mb-6 space-y-2">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Techniques avancées</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Analyse vidéo</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Sorties spéciales</li>
                    </ul>
                    <div class="text-center">
                        <a href="#contact" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-500 transition">Réserver</a>
                    </div>
                </div>
            </div>
            
            <div class="surf-card bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-48 bg-blue-100 relative">
                    <img src="https://images.unsplash.com/photo-1502680390469-be75c86b636f" alt="Cours avancé" class="w-full h-full object-cover">
                    <div class="absolute top-0 right-0 bg-red-500 text-white px-4 py-1 rounded-bl-lg font-bold">Avancé</div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-3">Expert</h3>
                    <p class="text-gray-600 mb-4">Maîtrisez les grosses vagues et perfectionnez vos manœuvres avec nos coaches expérimentés.</p>
                    <ul class="mb-6 space-y-2">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Coaching personnalisé</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Spots exclusifs</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Préparation compétition</li>
                    </ul>
                    <div class="text-center">
                        <a href="#contact" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-500 transition">Réserver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nos Coaches -->
<section id="coaches" class="py-20 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">Nos <span class="text-blue-600">Coaches</span></h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Une équipe de professionnels passionnés prêts à partager leur expérience avec vous</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="text-center">
                <div class="relative inline-block mb-6">
                    <img src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5" alt="Coach Yassine" class="rounded-full coach-image mx-auto">
                    <div class="absolute bottom-0 right-0 bg-blue-500 rounded-full p-2 text-white">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold mb-2">Yassine</h3>
                <p class="text-blue-600 mb-3">Coach Principal</p>
                <p class="text-gray-600 mb-4">10 ans d'expérience en enseignement du surf et ancien compétiteur national.</p>
                <div class="flex justify-center space-x-3">
                    <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            
            <div class="text-center">
                <div class="relative inline-block mb-6">
                    <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e" alt="Coach Fatima" class="rounded-full coach-image mx-auto">
                    <div class="absolute bottom-0 right-0 bg-blue-500 rounded-full p-2 text-white">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold mb-2">Fatima</h3>
                <p class="text-blue-600 mb-3">Spécialiste Débutants</p>
                <p class="text-gray-600 mb-4">Spécialiste des cours pour débutants et enfants avec une approche pédagogique unique.</p>
                <div class="flex justify-center space-x-3">
                    <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            
            <div class="text-center">
                <div class="relative inline-block mb-6">
                    <img src="https://images.unsplash.com/photo-1564564321837-a57b7070ac4f" alt="Coach Ali" class="rounded-full coach-image mx-auto">
                    <div class="absolute bottom-0 right-0 bg-blue-500 rounded-full p-2 text-white">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold mb-2">Ali</h3>
                <p class="text-blue-600 mb-3">Coach Performance</p>
                <p class="text-gray-600 mb-4">Compétiteur international et coach professionnel spécialisé en techniques avancées.</p>
                <div class="flex justify-center space-x-3">
                    <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sponsors Slider -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-10">Nos <span class="text-blue-600">Partenaires</span></h2>
        
        <div class="swiper sponsorSwiper">
            <div class="swiper-wrapper items-center">
                <!-- Utiliser des images de placeholder pour éviter les problèmes d'affichage -->
                <div class="swiper-slide flex justify-center"><img src="https://via.placeholder.com/150x80?text=Sponsor+1" class="h-16 object-contain" alt="Sponsor 1"></div>
                <div class="swiper-slide flex justify-center"><img src="https://via.placeholder.com/150x80?text=Sponsor+2" class="h-16 object-contain" alt="Sponsor 2"></div>
                <div class="swiper-slide flex justify-center"><img src="https://via.placeholder.com/150x80?text=Sponsor+3" class="h-16 object-contain" alt="Sponsor 3"></div>
                <div class="swiper-slide flex justify-center"><img src="https://via.placeholder.com/150x80?text=Sponsor+4" class="h-16 object-contain" alt="Sponsor 4"></div>
                <div class="swiper-slide flex justify-center"><img src="https://via.placeholder.com/150x80?text=Sponsor+5" class="h-16 object-contain" alt="Sponsor 5"></div>
                <div class="swiper-slide flex justify-center"><img src="https://via.placeholder.com/150x80?text=Sponsor+6" class="h-16 object-contain" alt="Sponsor 6"></div>
            </div>
            <div class="swiper-pagination mt-6"></div>
        </div>
    </div>
</section>

<!-- Météo -->
<section class="py-16 bg-gradient-to-r from-blue-500 to-blue-700 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-10">Conditions Actuelles à Essaouira</h2>
        
        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-8">
            <?php if (isset($weatherData) && !empty($weatherData)): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="text-5xl mb-2">
                            <i class="fas fa-temperature-high"></i>
                        </div>
                        <p class="text-2xl font-bold"><?= $weatherData['current']['temp_c'] ?? '--' ?>°C</p>
                        <p>Température</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-5xl mb-2">
                            <i class="fas fa-wind"></i>
                        </div>
                        <p class="text-2xl font-bold"><?= $weatherData['current']['wind_kph'] ?? '--' ?> km/h</p>
                        <p>Vent</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-5xl mb-2">
                            <i class="fas fa-water"></i>
                        </div>
                        <p class="text-2xl font-bold"><?= $weatherData['current']['humidity'] ?? '--' ?>%</p>
                        <p>Humidité</p>
                    </div>
                </div>
                <div class="mt-6 text-lg">
                    <p>Conditions parfaites pour le surf aujourd'hui !</p>
                </div>
            <?php else: ?>
                <div class="text-center">
                    <div class="text-5xl mb-4">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <p class="text-xl"><?= $weatherError ?? "Impossible de récupérer les données météo." ?></p>
                    <p class="mt-4">Contactez-nous pour connaître les conditions actuelles.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Contactez-nous -->
<section id="contact" class="py-20 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <h2 class="text-4xl font-bold mb-6">Contactez <span class="text-blue-600">Nous</span></h2>
                <p class="text-lg text-gray-600 mb-8">Vous avez des questions sur nos cours ou vous souhaitez faire une réservation ? N'hésitez pas à nous contacter !</p>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="bg-blue-600 p-3 rounded-full text-white mr-4">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-1">Adresse</h3>
                            <p class="text-gray-600">Plage d'Essaouira, Essaouira, Maroc</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-blue-600 p-3 rounded-full text-white mr-4">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-1">Téléphone</h3>
                            <p class="text-gray-600">+212 612 345 678</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-blue-600 p-3 rounded-full text-white mr-4">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-1">Email</h3>
                            <p class="text-gray-600">contact@surf-hube.com</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <form class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold mb-6">Envoyez-nous un message</h3>
                    
                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 mb-2">Nom</label>
                        <input type="text" id="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>
                    
                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>
                    
                    <div class="mb-6">
                        <label for="message" class="block text-gray-700 mb-2">Message</label>
                        <textarea id="message" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Envoyer</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Google Map -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-8">Nous <span class="text-blue-600">Trouver</span></h2>
        <div class="rounded-lg overflow-hidden shadow-lg">
            <iframe class="w-full h-96" src="https://maps.google.com/maps?q=Essaouira+beach&t=&z=14&ie=UTF8&iwloc=&output=embed" frameborder="0"></iframe>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-2xl font-bold mb-4">Surf-<span class="text-blue-400">Hube</span></h3>
                <p class="text-gray-400">La meilleure école de surf à Essaouira. Rejoignez-nous pour vivre une expérience inoubliable sur les vagues marocaines.</p>
            </div>
            
            <div>
                <h3 class="text-xl font-bold mb-4">Liens Rapides</h3>
                <ul class="space-y-2">
                    <li><a href="#home" class="text-gray-400 hover:text-blue-400 transition">Accueil</a></li>
                    <li><a href="#courses" class="text-gray-400 hover:text-blue-400 transition">Cours</a></li>
                    <li><a href="#coaches" class="text-gray-400 hover: