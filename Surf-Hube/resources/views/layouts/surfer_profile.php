<?php
include '../includes/db.php';

// Si l'utilisateur est connecté (ajoute ici une gestion de session si nécessaire)
session_start();
$surferId = $_SESSION['surfer_id']; // ID du surfeur depuis la session

// Récupérer les informations du surfeur
$query = $pdo->prepare("SELECT * FROM surfers WHERE id = ?");
$query->execute([$surferId]);
$surfer = $query->fetch(PDO::FETCH_ASSOC);

// Récupérer les réservations du surfeur
$reservationsQuery = $pdo->prepare("SELECT * FROM reservations WHERE surfer_id = ?");
$reservationsQuery->execute([$surferId]);
$reservations = $reservationsQuery->fetchAll(PDO::FETCH_ASSOC);

include '../layouts/surfer_header.php';
?>

<div class="container mx-auto p-6">
    <!-- Informations personnelles -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-2xl font-bold mb-4">Mon Profil</h2>
        <form action="update_profile.php" method="POST">
            <div class="space-y-4">
                <div>
                    <label for="nom" class="block text-lg font-semibold">Nom</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($surfer['nom']) ?>" class="w-full p-3 border border-gray-300 rounded" required>
                </div>

                <div>
                    <label for="email" class="block text-lg font-semibold">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($surfer['email']) ?>" class="w-full p-3 border border-gray-300 rounded" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg">Mettre à jour</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Réservations -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Mes Réservations</h2>
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left">Titre du Cours</th>
                    <th class="py-3 px-6">Date</th>
                    <th class="py-3 px-6">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td class="py-3 px-6"><?= htmlspecialchars($reservation['titre']) ?></td>
                        <td class="py-3 px-6"><?= htmlspecialchars($reservation['date_reservation']) ?></td>
                        <td class="py-3 px-6"><?= htmlspecialchars($reservation['statut']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../layouts/surfer_footer.php'; ?>
