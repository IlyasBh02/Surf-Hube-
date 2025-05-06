<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Connexion - Surf Hube</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-tr from-blue-100 to-blue-400 h-screen flex items-center justify-center">

  <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
    <h1 class="text-3xl font-bold text-blue-700 text-center mb-6">Connexion</h1>
    
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
      @csrf
      <input type="email" name="email" placeholder="Email" class="w-full px-4 py-2 border rounded-lg" required />
      <input type="password" name="password" placeholder="Mot de passe" class="w-full px-4 py-2 border rounded-lg" required />
      <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Se connecter</button>
    </form>
    <div class="my-4 text-center text-gray-500">ou</div>
    <button class="w-full flex items-center justify-center border border-gray-300 py-2 rounded-lg hover:bg-gray-100">
      <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5 mr-2"> Connexion avec Google
    </button>
    <p class="text-center mt-4 text-sm">
        Pas encore inscrit ?
        <a href="{{ route('register') }}" class="text-blue-800 font-semibold hover:underline">Créer un compte</a>
    </p>
  </div>

</body>
</html>
