<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background: linear-gradient(135deg, #ff6b00 0%, #ff8c00 100%);
        }
        
        .upload-area {
            border: 2px dashed #d1d5db;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .upload-area:hover {
            border-color: #ff6b00;
            background: #fff7ed;
        }
        
        .upload-icon {
            color: #9ca3af;
            font-size: 24px;
        }
        
        .input-field {
            border: 1px solid #d1d5db;
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            border-color: #ff6b00;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.1);
        }
        
        .btn-create {
            background: linear-gradient(135deg, #ff6b00 0%, #ff8c00 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 0, 0.3);
        }
        
        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 0, 0.4);
        }
        
        .section-title {
            color: #ff6b00;
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-section {
            background: #f8fafc;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
        }
        
        .sidebar-item {
            padding: 12px 20px;
            margin: 8px 0;
            border-radius: 8px;
            color: white;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-item.active {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .badge {
            background: #3b82f6;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-left: 8px;
        }
        
        .user-info {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: #ff6b00;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-gray-100 h-screen flex">
    <!-- Sidebar -->
    <div class="sidebar w-64 min-h-screen flex flex-col">
        <!-- Logo -->
        <div class="p-6 text-center">
            <h1 class="text-2xl font-bold text-white">Max It</h1>
            <p class="text-white/80 text-sm mt-1">SN</p>
        </div>
        
        <!-- User Info -->
        <div class="px-6 mb-6">
            <div class="user-info">
                <div class="user-avatar">
                    <i class='bx bx-user'></i>
                </div>
                <div>
                    <div class="font-medium text-gray-800">Fatousha</div>
                    <div class="text-sm text-gray-600">+221771234567</div>
                </div>
            </div>
        </div>
        
        <!-- Menu Items -->
        <div class="flex-1 px-6">
            <div class="sidebar-item">
                <button class="w-full text-left px-4 py-2 border border-white/30 rounded-lg">
                    Changer un compte
                </button>
            </div>
            
            <div class="sidebar-item active">
                <i class='bx bx-user-plus mr-3'></i>
                Basculer en Compte Principal
            </div>
            
            <div class="sidebar-item">
                <i class='bx bx-credit-card mr-3'></i>
                Comptes Secondaires
                <span class="badge">+29 • 32</span>
            </div>
            
            <div class="sidebar-item">
                <i class='bx bx-wallet mr-3'></i>
                Solde de compte principal
            </div>
            
            <div class="sidebar-item">
                <i class='bx bx-history mr-3'></i>
                Dix dernières transactions
            </div>
        </div>
        
        <!-- Déconnexion -->
        <div class="p-6">
            <div class="sidebar-item">
                <i class='bx bx-log-out mr-3'></i>
                Déconnexion
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto">
        <!-- Header -->
        <div class="bg-white shadow-sm p-6 border-b">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class='bx bx-search text-gray-400 mr-3'></i>
                    <input type="text" placeholder="Rechercher..." class="border-none outline-none text-gray-600 w-96">
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="p-8 max-w-4xl">
            <div class="section-title">
                <i class='bx bx-user-circle'></i>
                Informations Compte Principal
            </div>

            <?php $errors = $_SESSION['flash_errors'] ?? []; ?>
            <?php $old = $_SESSION['old_input'] ?? []; ?>

            <form method="post" action="<?= getenv('BASE_URL') ?>/inscription" enctype="multipart/form-data">
                <input type="hidden" name="debug" value="1">

                <!-- Informations personnelles -->
                <div class="form-section">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Prénom *</label>
                            <input type="text" name="prenom" value="<?= htmlspecialchars($old['prenom'] ?? '') ?>" 
                                   class="input-field w-full px-4 py-3 rounded-lg" placeholder="Entrez votre prénom">
                            <?php if (!empty($errors['prenom'])): ?>
                                <div class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['prenom']) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Nom *</label>
                            <input type="text" name="nom" value="<?= htmlspecialchars($old['nom'] ?? '') ?>" 
                                   class="input-field w-full px-4 py-3 rounded-lg" placeholder="Entrez votre nom">
                            <?php if (!empty($errors['nom'])): ?>
                                <div class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['nom']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Coordonnées -->
                <div class="form-section">
                    <div class="section-title">
                        <i class='bx bx-phone'></i>
                        Coordonnées
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Numéro de téléphone *</label>
                        <input type="tel" name="telephone" value="<?= htmlspecialchars($old['telephone'] ?? '') ?>" 
                               class="input-field w-full px-4 py-3 rounded-lg" placeholder="Entrez votre numéro de téléphone">
                        <?php if (!empty($errors['telephone'])): ?>
                            <div class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['telephone']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Adresse *</label>
                        <textarea name="adresse" rows="3" class="input-field w-full px-4 py-3 rounded-lg" 
                                  placeholder="Entrez votre adresse"><?= htmlspecialchars($old['adresse'] ?? '') ?></textarea>
                        <?php if (!empty($errors['adresse'])): ?>
                            <div class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['adresse']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">
                        <i class='bx bx-id-card'></i>
                        Documents d'Identité
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Numéro de Carte d'Identité *</label>
                        <input type="text" name="numeroIdentite" value="<?= htmlspecialchars($old['numeroIdentite'] ?? '') ?>" 
                               class="input-field w-full px-4 py-3 rounded-lg" placeholder="Entrez votre numéro de CNI">
                        <?php if (!empty($errors['numeroIdentite'])): ?>
                            <div class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['numeroIdentite']) ?></div>
                        <?php endif; ?>
                    </div>
                       
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Photo resto de la carte d'identité *</label>
                            <div class="upload-area rounded-lg p-8 text-center cursor-pointer">
                                <i class='bx bx-download upload-icon mb-3'></i>
                                <p class="text-gray-500">cliquez pour télécharger</p>
                                <input type="file" name="photorecto" accept="image/*" class="hidden">
                            </div>
                            <?php if (!empty($errors['photorecto'])): ?>
                                <div class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['photorecto']) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Photo verso de la carte d'identité *</label>
                            <div class="upload-area rounded-lg p-8 text-center cursor-pointer">
                                <i class='bx bx-download upload-icon mb-3'></i>
                                <p class="text-gray-500">cliquez pour télécharger</p>
                                <input type="file" name="photoverso" accept="image/*" class="hidden">
                            </div>
                            <?php if (!empty($errors['photoverso'])): ?>
                                <div class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['photoverso']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Informations de connexion -->
                <div class="form-section">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Login</label>
                            <input type="text" name="login" value="<?= htmlspecialchars($old['login'] ?? '') ?>" 
                                   class="input-field w-full px-4 py-3 rounded-lg" placeholder="Entrez votre login">
                            <?php if (!empty($errors['login'])): ?>
                                <div class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['login']) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Mot de passe</label>
                            <input type="password" name="password" 
                                   class="input-field w-full px-4 py-3 rounded-lg" placeholder="Entrez votre mot de passe">
                            <?php if (!empty($errors['password'])): ?>
                                <div class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['password']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <button type="submit" class="btn-create w-full text-white py-4 rounded-lg font-medium text-lg flex items-center justify-center gap-2">
                    Créer un compte
                    <i class='bx bx-right-arrow-alt'></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        // Gestion des zones de téléchargement
        document.querySelectorAll('.upload-area').forEach(area => {
            const input = area.querySelector('input[type="file"]');
            
            area.addEventListener('click', () => {
                input.click();
            });
            
            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const icon = area.querySelector('.upload-icon');
                    const text = area.querySelector('p');
                    
                    icon.className = 'bx bx-check upload-icon mb-3';
                    icon.style.color = '#22c55e';
                    text.textContent = file.name;
                    text.style.color = '#22c55e';
                }
            });
        });
    </script>
</body>

</html>
<?php unset($_SESSION['flash_errors'], $_SESSION['old_input']); ?>