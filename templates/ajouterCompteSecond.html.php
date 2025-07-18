<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un compte secondaire</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .orange-gradient {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }
        .orange-gradient:hover {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
            transform: translateY(-1px);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="ml-64 p-6">
        <!-- Header avec barre de recherche et profil utilisateur -->
        <div class="flex justify-between items-center mb-8">
            <div></div>
            <div class="flex items-center space-x-6">
                <!-- Barre de recherche -->
                <div class="relative">
                    <input type="text" placeholder="Rechercher..." class="bg-white rounded-full px-10 py-2 w-64 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <i class='bx bx-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                </div>
                
                <!-- Profil utilisateur -->
                <div class="flex items-center space-x-3">
                    <div class="bg-orange-500 rounded-full w-10 h-10 flex items-center justify-center text-white">
                        <i class='bx bx-user text-xl'></i>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800">Fatousha</div>
                        <div class="text-sm text-gray-500">+221771234567</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-2xl mx-auto bg-white rounded-xl p-10 card-shadow">
            <h2 class="text-3xl font-bold text-orange-500 mb-6 text-center">Ajouter un compte secondaire</h2>
            
            <form method="POST" action="/ajouterCompteSecond" class="space-y-8">
                <div>
                    <label class="block text-orange-500 font-semibold mb-2 text-lg">Numéro de téléphone</label>
                    <input type="text" name="numero" class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300" placeholder="Ex: +221771234567" required>
                </div>

                <div>
                    <label class="block text-orange-500 font-semibold mb-2 text-lg">Solde initial</label>
                    <input type="number" name="solde" class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300" placeholder="Ex: 1000" min="0" step="0.01" required>
                </div>

                <button type="submit" class="orange-gradient text-white rounded-lg px-6 py-4 font-semibold text-lg w-full hover:shadow-lg transition-all duration-300 flex items-center justify-center space-x-2">
                    <i class='bx bx-plus text-xl'></i>
                    <span>Créer un compte</span>
                    <i class='bx bx-right-arrow-alt text-xl'></i>
                </button>
            </form>

            <?php if ($message): ?>
                <div class="mt-6 p-4 bg-orange-50 border border-orange-200 rounded-lg text-center text-orange-700 font-medium">
                    <?= $message ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>