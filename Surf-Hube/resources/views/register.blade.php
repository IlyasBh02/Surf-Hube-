<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - École de Surf</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-400 to-indigo-600 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 space-y-6">
        <h2 class="text-3xl font-bold text-center text-gray-800">Créer un compte</h2>

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf
            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block text-gray-700">Nom</label>
                    <input type="text" name="nom" required class="w-full border border-gray-300 p-2 rounded" placeholder="Votre nom">
                </div>
                <div class="w-1/2">
                    <label class="block text-gray-700">Prénom</label>
                    <input type="text" name="prenom" required class="w-full border border-gray-300 p-2 rounded" placeholder="Votre prénom">
                </div>
            </div>

            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" required class="w-full border border-gray-300 p-2 rounded" placeholder="exemple@email.com">
            </div>

            <div>
                <label class="block text-gray-700">Mot de passe</label>
                <input type="password" name="password" required class="w-full border border-gray-300 p-2 rounded" placeholder="••••••••">
            </div>

            <div>
                <label class="block text-gray-700">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required class="w-full border border-gray-300 p-2 rounded" placeholder="••••••••">
            </div>

            <div>
                <label class="block text-gray-700">Sexe</label>
                <select name="sexe" class="w-full border border-gray-300 p-2 rounded" required>
                    <option value="">-- Sélectionner --</option>
                    <option value="homme">Homme</option>
                    <option value="femme">Femme</option>
                </select>
            </div>

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block text-gray-700">Poids (kg)</label>
                    <input type="number" name="poids" min="1" required class="w-full border border-gray-300 p-2 rounded" placeholder="ex: 70">
                </div>
                <div class="w-1/2">
                    <label class="block text-gray-700">Hauteur (cm)</label>
                    <input type="number" name="hauteur" min="1" required class="w-full border border-gray-300 p-2 rounded" placeholder="ex: 175">
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="experience" id="experience" class="mr-2">
                <label for="experience" class="text-gray-700">J'ai déjà une expérience en surf</label>
            </div>

            <div>
                <label class="block text-gray-700">Je souhaite m'inscrire en tant que</label>
                <select name="role" class="w-full border border-gray-300 p-2 rounded" required>
                    <option value="">-- Sélectionner --</option>
                    <option value="surfer">Surfeur</option>
                    <option value="coach">Coach</option>
                </select>
                <p class="text-sm text-gray-500 mt-1">Note : Les coachs doivent être approuvés par un administrateur avant de pouvoir donner des cours.</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                S'inscrire
            </button>

            <div class="flex items-center justify-center">
                <span class="text-gray-500">ou</span>
            </div>

            <button type="button" onclick="window.location.href='https://accounts.google.com/o/oauth2/v2/auth'" class="w-full flex items-center justify-center gap-3 border border-gray-300 py-2 rounded hover:bg-gray-100 transition">
                <img src="https://www.svgrepo.com/show/355037/google.svg" class="h-5 w-5" alt="Google logo">
                <span>Continuer avec Google</span>
            </button>
        </form>

        <p class="text-center mt-4 text-sm">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="text-blue-800 font-semibold hover:underline">Se connecter</a>
        </p>
    </div>
</body>
</html>
