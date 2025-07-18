<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Maxit</title>
    <script src="https://cdn.tailwindcss.com"></script>
   
        
       
    <style>
        body {
           background: linear-gradient(to bottom, rgba(250, 122, 18, 1), #ff7842ff 0%, #000000 100%);
            position: relative;
            overflow: hidden;
        }
        
        .decorative-circles::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            top: -150px;
            left: -150px;
        }
        
        .decorative-circles::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background:rgb(253, 145, 4);
            bottom: -100px;
            right: -100px;
        }
        
        .decorative-circles .circle-small {
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            top: 20%;
            left: 5%;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .decorative-circles .circle-medium {
            position: absolute;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            bottom: 30%;
            right: 10%;
        }
        
        .decorative-circles .circle-tiny {
            position: absolute;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background:rgb(250, 153, 7);
            bottom: 10%;
            left: 20%;
        }
        
        .decorative-circles .circle-mini {
            position: absolute;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #ffffff;
            bottom: 15%;
            right: 30%;
        }
        
        .decorative-circles .circle-micro {
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #ffffff;
            top: 70%;
            right: 20%;
        }

        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
       
        
        .logo-container {
            background: #ff6b00;
            border-radius: 8px;
            padding: 12px;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .logo-text {
            color: white;
            font-weight: bold;
            font-size: 18px;
        }
        
        .input-field {
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid rgba(255, 107, 0, 0.3);
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: #ff6b00;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.1);
        }
        
        .btn-connect {
            background: #ff6b00;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 0, 0.3);
        }
        
        .btn-connect:hover {
            background: #e55a00;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 0, 0.4);
        }
        
        .error-message {
            background: rgba(220, 38, 38, 0.1);
            border: 1px solid rgba(220, 38, 38, 0.3);
            color: #dc2626;
        }
        
        .success-message {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #22c55e;
        }
    </style>
</head>

<body class="h-screen w-screen flex items-center justify-center relative">
    <div class="decorative-circles absolute inset-0">
        <div class="circle-small"></div>
        <div class="circle-medium"></div>
        <div class="circle-tiny"></div>
        <div class="circle-mini"></div>
        <div class="circle-micro"></div>
    </div>
    <div class="glass-card rounded-3xl shadow-2xl p-8 w-full max-w-md mx-4 relative z-10">
        <div class="text-center mb-8">
            <div class="logo-container">
                <span class="logo-text">Max It</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mt-4">Bienvenue sur votre application <span class="text-orange-500">max</span> it</h1>
            <p class="text-gray-600 mt-2">Connectez-vous à votre compte max it</p>
        </div>

        <?php if (isset($success) && $success): ?>
            <div class="success-message px-4 py-3 rounded-lg mb-6">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error) && $error): ?>
            <div class="error-message px-4 py-3 rounded-lg mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($errors) && is_array($errors) && !empty($errors)): ?>
            <div class="error-message px-4 py-3 rounded-lg mb-6">
                <?php if (isset($errors['global'])): ?>
                    <p><?= htmlspecialchars($errors['global']) ?></p>
                <?php endif; ?>
                
                <?php if (count($errors) > (isset($errors['global']) ? 1 : 0)): ?>
                    <ul class="list-disc ml-5">
                        <?php foreach ($errors as $field => $message): ?>
                            <?php if ($field !== 'global'): ?>
                                <li><?= htmlspecialchars($message) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-orange-500 mb-2">Numéro Téléphone</label>
                <input type="text" 
                       placeholder="Numéro Tél..."
                       name="login" 
                       value="<?= htmlspecialchars($old['login'] ?? '') ?>" 
                       class="input-field w-full px-4 py-3 rounded-lg text-gray-700 placeholder-gray-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-orange-500 mb-2">Password</label>
                <input placeholder="Password..." 
                       type="password" 
                       name="password" 
                       class="input-field w-full px-4 py-3 rounded-lg text-gray-700 placeholder-gray-500">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" class="rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
                <a href="#" class="text-sm text-orange-500 hover:text-orange-600">forgot password</a>
            </div>

            <button type="submit" class="btn-connect w-full text-white py-4 rounded-lg font-medium text-lg">
                Connecter
            </button>
        </form>

        <div class="mt-6 text-center text-sm">
            <a href="#" class="text-gray-600 hover:text-gray-800">Mot de passe oublié?</a>
            <a href="/inscription" class="text-orange-500 hover:text-orange-600 ml-3">S'inscrire</a>
        </div>
    </div>
</body>

</html>
<?php unset($_SESSION['flash_errors'], $_SESSION['old_input']); ?>