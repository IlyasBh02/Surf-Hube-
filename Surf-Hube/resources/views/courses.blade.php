<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nos Cours de Surf - Surf-Hube</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.7);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 70%;
            max-width: 600px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
        .calendar-day {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 2px;
            border-radius: 50%;
            cursor: pointer;
        }
        .available {
            background-color: #10B981;
            color: white;
        }
        .booked {
            background-color: #EF4444;
            color: white;
            cursor: not-allowed;
        }
        .today {
            border: 2px solid #3B82F6;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="max-w-screen-xl mx-auto flex items-center justify-between px-4 py-4">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">Surf-Hube</a>
            <div class="space-x-6">
                <a href="{{ route('home') }}" class="hover:text-blue-400">Accueil</a>
                <a href="{{ route('courses') }}" class="text-blue-600 font-medium">Cours</a>
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

    <!-- Hero Section -->
    <div class="bg-blue-600 text-white py-16">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Nos Cours de Surf</h1>
            <p class="text-xl">Découvrez nos différents programmes adaptés à tous les niveaux</p>
            <div class="mt-6">
                <a href="#catalog" class="inline-block bg-white text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition duration-300">
                    Voir le catalogue complet
                </a>
            </div>
        </div>
    </div>

    <!-- Courses Catalog -->
    <div id="catalog" class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Course Card 1: Débutant -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://images.unsplash.com/photo-1531722569936-825d3dd91b15?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Cours Débutant" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Débutant</h3>
                    <p class="text-gray-600 mb-4">Apprenez les bases du surf et attrapez votre première vague.</p>
                    <div class="flex justify-between">
                        <button class="info-btn bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" data-course="debutant">Plus d'infos</button>
                        @auth
                            <button class="book-btn bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600" data-course="debutant">Réserver</button>
                        @else
                            <a href="{{ route('login') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Connectez-vous pour réserver</a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Course Card 2: Intermédiaire -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://images.unsplash.com/photo-1502680390469-be75c86b636f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Cours Intermédiaire" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Intermédiaire</h3>
                    <p class="text-gray-600 mb-4">Perfectionnez votre technique et commencez à manœuvrer sur les vagues.</p>
                    <div class="flex justify-between">
                        <button class="info-btn bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" data-course="intermediaire">Plus d'infos</button>
                        @auth
                            <button class="book-btn bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600" data-course="intermediaire">Réserver</button>
                        @else
                            <a href="{{ route('login') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Connectez-vous pour réserver</a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Course Card 3: Avancé -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Cours Avancé" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Avancé</h3>
                    <p class="text-gray-600 mb-4">Améliorez vos compétences et dominez les plus grosses vagues.</p>
                    <div class="flex justify-between">
                        <button class="info-btn bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" data-course="avance">Plus d'infos</button>
                        @auth
                            <button class="book-btn bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600" data-course="avance">Réserver</button>
                        @else
                            <a href="{{ route('login') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Connectez-vous pour réserver</a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Course Card 4: Compétition -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://images.unsplash.com/photo-1519046904884-53103b34b206?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Cours Compétition" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Compétition</h3>
                    <p class="text-gray-600 mb-4">Préparez-vous pour les compétitions avec nos coachs professionnels.</p>
                    <div class="flex justify-between">
                        <button class="info-btn bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" data-course="competition">Plus d'infos</button>
                        @auth
                            <button class="book-btn bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600" data-course="competition">Réserver</button>
                        @else
                            <a href="{{ route('login') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Connectez-vous pour réserver</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Future Activities Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Activités à Venir</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Activity 1 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1519046904884-53103b34b206?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Stage d'été" class="w-full h-48 object-cover">
                        <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                            15-30 Juillet
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Stage d'été</h3>
                        <p class="text-gray-600 mb-4">Un stage intensif de deux semaines pour progresser rapidement, idéal pour les vacances d'été.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-600 font-medium">1200 MAD</span>
                            <div class="space-x-2">
                                <button class="activity-info-btn bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600" data-activity="stage">Infos</button>
                                @auth
                                    <button class="activity-book-btn bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600" data-activity="stage">Réserver</button>
                                @else
                                    <a href="{{ route('login') }}" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">Connectez-vous</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Activity 2 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1502680390469-be75c86b636f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Surf & Yoga" class="w-full h-48 object-cover">
                        <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                            Tous les weekends
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Surf & Yoga</h3>
                        <p class="text-gray-600 mb-4">Combine le surf avec des séances de yoga pour une expérience complète de bien-être.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-600 font-medium">450 MAD</span>
                            <div class="space-x-2">
                                <button class="activity-info-btn bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600" data-activity="yoga">Infos</button>
                                @auth
                                    <button class="activity-book-btn bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600" data-activity="yoga">Réserver</button>
                                @else
                                    <a href="{{ route('login') }}" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">Connectez-vous</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Activity 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1531722569936-825d3dd91b15?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Cours pour enfants" class="w-full h-48 object-cover">
                        <div class="absolute top-4 right-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                            Juin - Août
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Cours pour enfants</h3>
                        <p class="text-gray-600 mb-4">Des cours spécialement conçus pour les enfants de 6 à 12 ans, avec des moniteurs expérimentés.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-600 font-medium">250 MAD</span>
                            <div class="space-x-2">
                                <button class="activity-info-btn bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600" data-activity="enfants">Infos</button>
                                @auth
                                    <button class="activity-book-btn bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600" data-activity="enfants">Réserver</button>
                                @else
                                    <a href="{{ route('login') }}" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">Connectez-vous</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="#catalog" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                    Voir toutes les activités
                </a>
            </div>
        </div>
    </div>

    <!-- Info Modal -->
    <div id="infoModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="infoTitle" class="text-2xl font-bold mb-4"></h2>
            <div id="infoContent" class="text-gray-700"></div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="bookingTitle" class="text-2xl font-bold mb-4"></h2>
            <div class="mb-4">
                <h3 class="text-lg font-semibold mb-2">Disponibilités</h3>
                <div id="calendar" class="flex flex-wrap justify-center"></div>
            </div>
            <div id="bookingForm" class="hidden">
                <form id="reservationForm" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
                        <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                        <input type="tel" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" id="date" name="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="time" class="block text-sm font-medium text-gray-700">Heure</label>
                        <select id="time" name="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                            <option value="11:00">11:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                        </select>
                    </div>
                    <div>
                        <label for="participants" class="block text-sm font-medium text-gray-700">Nombre de participants</label>
                        <input type="number" id="participants" name="participants" min="1" max="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes supplémentaires</label>
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Confirmer la réservation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Info Modal
        $('.info-btn').click(function() {
            const course = $(this).data('course');
            let title = '';
            let content = '';
            
            switch(course) {
                case 'debutant':
                    title = 'Cours Débutant';
                    content = `
                        <p class="mb-4">Nos cours pour débutants sont conçus pour vous initier aux bases du surf de manière sécurisée et ludique.</p>
                        <h4 class="font-bold mb-2">Ce que vous apprendrez :</h4>
                        <ul class="list-disc pl-5 mb-4">
                            <li>Les règles de sécurité en mer</li>
                            <li>Comment lire les vagues</li>
                            <li>Les positions de base sur la planche</li>
                            <li>Comment se lever sur la planche</li>
                            <li>Les premières manœuvres</li>
                        </ul>
                        <h4 class="font-bold mb-2">Informations pratiques :</h4>
                        <ul class="list-disc pl-5">
                            <li>Durée : 2 heures</li>
                            <li>Prix : 300 MAD par personne</li>
                            <li>Équipement fourni : planche et combinaison</li>
                            <li>Niveau requis : aucun</li>
                        </ul>
                    `;
                    break;
                case 'intermediaire':
                    title = 'Cours Intermédiaire';
                    content = `
                        <p class="mb-4">Pour les surfeurs ayant déjà les bases, nos cours intermédiaires vous permettront de progresser et d'affiner votre technique.</p>
                        <h4 class="font-bold mb-2">Ce que vous apprendrez :</h4>
                        <ul class="list-disc pl-5 mb-4">
                            <li>Techniques de virage</li>
                            <li>Lecture avancée des vagues</li>
                            <li>Positionnement dans la vague</li>
                            <li>Techniques de rame</li>
                            <li>Manœuvres de base</li>
                        </ul>
                        <h4 class="font-bold mb-2">Informations pratiques :</h4>
                        <ul class="list-disc pl-5">
                            <li>Durée : 2 heures</li>
                            <li>Prix : 350 MAD par personne</li>
                            <li>Équipement fourni : planche et combinaison</li>
                            <li>Niveau requis : bases du surf</li>
                        </ul>
                    `;
                    break;
                case 'avance':
                    title = 'Cours Avancé';
                    content = `
                        <p class="mb-4">Pour les surfeurs expérimentés, nos cours avancés vous permettront de perfectionner votre technique et de surfer des vagues plus difficiles.</p>
                        <h4 class="font-bold mb-2">Ce que vous apprendrez :</h4>
                        <ul class="list-disc pl-5 mb-4">
                            <li>Manœuvres avancées</li>
                            <li>Surf en conditions difficiles</li>
                            <li>Techniques de compétition</li>
                            <li>Analyse vidéo de votre surf</li>
                            <li>Conseils personnalisés</li>
                        </ul>
                        <h4 class="font-bold mb-2">Informations pratiques :</h4>
                        <ul class="list-disc pl-5">
                            <li>Durée : 2 heures</li>
                            <li>Prix : 400 MAD par personne</li>
                            <li>Équipement fourni : planche et combinaison</li>
                            <li>Niveau requis : niveau intermédiaire</li>
                        </ul>
                    `;
                    break;
                case 'competition':
                    title = 'Cours Compétition';
                    content = `
                        <p class="mb-4">Préparez-vous pour les compétitions avec nos coachs professionnels qui vous guideront pour atteindre votre plein potentiel.</p>
                        <h4 class="font-bold mb-2">Ce que vous apprendrez :</h4>
                        <ul class="list-disc pl-5 mb-4">
                            <li>Stratégies de compétition</li>
                            <li>Techniques avancées</li>
                            <li>Préparation physique</li>
                            <li>Analyse vidéo détaillée</li>
                            <li>Conseils de champions</li>
                        </ul>
                        <h4 class="font-bold mb-2">Informations pratiques :</h4>
                        <ul class="list-disc pl-5">
                            <li>Durée : 2 heures</li>
                            <li>Prix : 450 MAD par personne</li>
                            <li>Équipement fourni : planche et combinaison</li>
                            <li>Niveau requis : niveau avancé</li>
                        </ul>
                    `;
                    break;
            }
            
            $('#infoTitle').text(title);
            $('#infoContent').html(content);
            $('#infoModal').show();
        });
        
        // Activity Info Modal
        $('.activity-info-btn').click(function() {
            const activity = $(this).data('activity');
            let title = '';
            let content = '';
            
            switch(activity) {
                case 'stage':
                    title = 'Stage d\'été';
                    content = `
                        <p class="mb-4">Un stage intensif de deux semaines pour progresser rapidement, idéal pour les vacances d'été.</p>
                        <h4 class="font-bold mb-2">Programme :</h4>
                        <ul class="list-disc pl-5 mb-4">
                            <li>Cours quotidiens de surf</li>
                            <li>Analyse vidéo</li>
                            <li>Théorie et pratique</li>
                            <li>Activités en groupe</li>
                            <li>Compétition amicale</li>
                        </ul>
                        <h4 class="font-bold mb-2">Informations pratiques :</h4>
                        <ul class="list-disc pl-5">
                            <li>Dates : 15-30 Juillet</li>
                            <li>Prix : 1200 MAD</li>
                            <li>Équipement fourni</li>
                            <li>Niveaux acceptés : tous</li>
                        </ul>
                    `;
                    break;
                case 'yoga':
                    title = 'Surf & Yoga';
                    content = `
                        <p class="mb-4">Combine le surf avec des séances de yoga pour une expérience complète de bien-être.</p>
                        <h4 class="font-bold mb-2">Programme :</h4>
                        <ul class="list-disc pl-5 mb-4">
                            <li>Séance de yoga (1h)</li>
                            <li>Cours de surf (2h)</li>
                            <li>Exercices de respiration</li>
                            <li>Étirements et relaxation</li>
                        </ul>
                        <h4 class="font-bold mb-2">Informations pratiques :</h4>
                        <ul class="list-disc pl-5">
                            <li>Dates : Tous les weekends</li>
                            <li>Prix : 450 MAD</li>
                            <li>Équipement fourni</li>
                            <li>Niveaux acceptés : tous</li>
                        </ul>
                    `;
                    break;
                case 'enfants':
                    title = 'Cours pour enfants';
                    content = `
                        <p class="mb-4">Des cours spécialement conçus pour les enfants de 6 à 12 ans, avec des moniteurs expérimentés.</p>
                        <h4 class="font-bold mb-2">Programme :</h4>
                        <ul class="list-disc pl-5 mb-4">
                            <li>Apprentissage ludique</li>
                            <li>Jeux d'eau</li>
                            <li>Exercices d'équilibre</li>
                            <li>Techniques adaptées aux enfants</li>
                            <li>Activités de groupe</li>
                        </ul>
                        <h4 class="font-bold mb-2">Informations pratiques :</h4>
                        <ul class="list-disc pl-5">
                            <li>Dates : Juin - Août</li>
                            <li>Prix : 250 MAD</li>
                            <li>Équipement fourni</li>
                            <li>Âge : 6-12 ans</li>
                        </ul>
                    `;
                    break;
            }
            
            $('#infoTitle').text(title);
            $('#infoContent').html(content);
            $('#infoModal').show();
        });
        
        // Booking Modal
        $('.book-btn, .activity-book-btn').click(function() {
            const course = $(this).data('course') || $(this).data('activity');
            let title = '';
            
            switch(course) {
                case 'debutant':
                    title = 'Réserver un cours Débutant';
                    break;
                case 'intermediaire':
                    title = 'Réserver un cours Intermédiaire';
                    break;
                case 'avance':
                    title = 'Réserver un cours Avancé';
                    break;
                case 'competition':
                    title = 'Réserver un cours Compétition';
                    break;
                case 'stage':
                    title = 'Réserver le Stage d\'été';
                    break;
                case 'yoga':
                    title = 'Réserver Surf & Yoga';
                    break;
                case 'enfants':
                    title = 'Réserver un cours pour enfants';
                    break;
            }
            
            $('#bookingTitle').text(title);
            $('#bookingModal').show();
            
            // Générer le calendrier
            generateCalendar();
        });
        
        // Close modals
        $('.close').click(function() {
            $('.modal').hide();
        });
        
        // Close modal when clicking outside
        $(window).click(function(event) {
            if ($(event.target).hasClass('modal')) {
                $('.modal').hide();
            }
        });
        
        // Generate calendar
        function generateCalendar() {
            const calendar = $('#calendar');
            calendar.empty();
            
            const today = new Date();
            const currentMonth = today.getMonth();
            const currentYear = today.getFullYear();
            
            const firstDay = new Date(currentYear, currentMonth, 1);
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            
            const daysInMonth = lastDay.getDate();
            const startingDay = firstDay.getDay();
            
            const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            
            calendar.append(`<div class="w-full text-center font-bold mb-4">${monthNames[currentMonth]} ${currentYear}</div>`);
            
            const daysOfWeek = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
            const daysHeader = $('<div class="flex w-full mb-2"></div>');
            
            daysOfWeek.forEach(day => {
                daysHeader.append(`<div class="w-1/7 text-center text-sm font-medium">${day}</div>`);
            });
            
            calendar.append(daysHeader);
            
            const daysGrid = $('<div class="flex flex-wrap w-full"></div>');
            
            // Add empty cells for days before the first day of the month
            for (let i = 0; i < startingDay; i++) {
                daysGrid.append('<div class="w-1/7 h-10"></div>');
            }
            
            // Add cells for each day of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(currentYear, currentMonth, day);
                const isToday = date.toDateString() === today.toDateString();
                const isPast = date < today;
                
                let dayClass = 'calendar-day w-1/7 h-10 flex items-center justify-center';
                
                if (isPast) {
                    dayClass += ' bg-gray-200 text-gray-500 cursor-not-allowed';
                } else {
                    // Simulate availability (in a real app, this would come from the backend)
                    const isAvailable = Math.random() > 0.3;
                    const isBooked = Math.random() > 0.7;
                    
                    if (isBooked) {
                        dayClass += ' booked';
                    } else if (isAvailable) {
                        dayClass += ' available';
                    } else {
                        dayClass += ' bg-gray-200 text-gray-500 cursor-not-allowed';
                    }
                }
                
                if (isToday) {
                    dayClass += ' today';
                }
                
                daysGrid.append(`<div class="${dayClass}">${day}</div>`);
            }
            
            calendar.append(daysGrid);
            
            // Show booking form when a day is selected
            $('.calendar-day.available').click(function() {
                const selectedDay = $(this).text();
                const selectedDate = new Date(currentYear, currentMonth, selectedDay);
                const formattedDate = selectedDate.toISOString().split('T')[0];
                
                $('#date').val(formattedDate);
                $('#calendar').hide();
                $('#bookingForm').removeClass('hidden');
            });
        }
        
        // Handle form submission
        $('#reservationForm').submit(function(e) {
            e.preventDefault();
            
            // In a real app, this would send the data to the server
            alert('Réservation confirmée ! Nous vous contacterons bientôt pour finaliser les détails.');
            $('#bookingModal').hide();
            $('#bookingForm').addClass('hidden');
            $('#calendar').show();
        });
    </script>
</body>
</html> 