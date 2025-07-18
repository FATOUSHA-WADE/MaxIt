<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        }
        .orange-gradient {
            background: linear-gradient(135deg, #ff7b00 0%, #ff9500 100%);
        }
        .card-shadow {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .balance-card {
            background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
            border: 1px solid #333;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
  


    <div class="ml-64 p-6">
        <div class="flex justify-between items-center mb-6">
            <div></div>
            <div class="flex items-center space-x-6">
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

        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="balance-card rounded-xl p-6 text-white card-shadow">
                    <div class="flex items-center mb-2">
                        <i class='bx bx-credit-card text-2xl text-orange-500 mr-3'></i>
                        <span class="text-orange-500 font-semibold">Compte Principal</span>
                    </div>
                    <div class="text-sm text-gray-400 mb-2">+221771234567</div>
                    <div class="text-sm text-gray-400 mb-4">Solde disponible</div>
                    <div class="text-2xl font-bold text-white">
                        <?= !empty($comptes) ? number_format($comptes[0]['solde'], 0, ',', ' ') : '50 000' ?> FCFA
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 card-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="text-gray-600 text-sm">Transactions ce mois</div>
                            <div class="text-2xl font-bold text-gray-800">20</div>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <i class='bx bx-trending-up text-green-500 text-xl'></i>
                        </div>
                    </div>
                    <div class="text-sm text-green-600">+12% par rapport au mois dernier</div>
                </div>

                <div class="bg-white rounded-xl p-6 card-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="text-gray-600 text-sm">Comptes actifs</div>
                            <div class="text-2xl font-bold text-gray-800">1</div>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <i class='bx bx-wallet text-blue-500 text-xl'></i>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">1 principale</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
                <div class="space-y-4">
                </div>

                <div class="lg:col-span-1">
                    <div class="flex justify-between items-center mb-6">
                        <div></div>
                        <a href="/ajouterCompteSecond" class="orange-gradient text-white rounded-lg px-4 py-2 font-medium hover:opacity-90 transition-opacity">
                            Ajouter Compte Secondaire
                        </a>
                    </div>

                    <div class="bg-white rounded-xl p-6 card-shadow">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Historique des transactions</h3>
                            <button class="text-blue-500 hover:text-blue-600 text-sm font-medium">
                                Voir plus
                            </button>
                        </div>

                        <div class="space-y-4">
                            <?php 
                            if (!empty($transactions)): 
                                $count = 0;
                                foreach ($transactions as $transaction):
                                    if ($count >= 10) break; 
                                    
                                    $isDeposit = $transaction['type'] === 'depot';
                                    $iconClass = $isDeposit ? 'bx-arrow-to-bottom text-green-500' : 'bx-arrow-from-bottom text-red-500';
                                    $amountClass = $isDeposit ? 'text-green-600' : 'text-red-600';
                                    $bgClass = $isDeposit ? 'bg-green-50' : 'bg-red-50';
                                    $amountPrefix = $isDeposit ? '+' : '-';
                            ?>
                                <div class="flex items-center justify-between p-4 <?= $bgClass ?> rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div class="<?= $isDeposit ? 'bg-green-100' : 'bg-red-100' ?> rounded-full p-2">
                                            <i class='bx <?= $iconClass ?> text-xl'></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800">
                                                <?= $isDeposit ? 'Dépôt espèces' : 'Paiement facture électricité' ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= $isDeposit ? 'Transfert • Dépôt' : 'Paiement • Factures' ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold <?= $amountClass ?>">
                                            <?= $amountPrefix ?><?= number_format($transaction['montant'], 0, ',', ' ') ?> FCFA
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= date('d/m/Y') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                    $count++;
                                endforeach;
                            else:
                                $sampleTransactions = [
                                    ['type' => 'depot', 'montant' => 25000, 'date' => '16/01/2024'],
                                    ['type' => 'retrait', 'montant' => 10000, 'date' => '14/01/2024'],
                                    ['type' => 'retrait', 'montant' => 16000, 'date' => '14/01/2024'],
                                    ['type' => 'depot', 'montant' => 5000, 'date' => '13/01/2024']
                                ];
                                
                                foreach ($sampleTransactions as $transaction):
                                    $isDeposit = $transaction['type'] === 'depot';
                                    $iconClass = $isDeposit ? 'bx-arrow-to-bottom text-green-500' : 'bx-arrow-from-bottom text-red-500';
                                    $amountClass = $isDeposit ? 'text-green-600' : 'text-red-600';
                                    $bgClass = $isDeposit ? 'bg-green-50' : 'bg-red-50';
                                    $amountPrefix = $isDeposit ? '+' : '-';
                                    $title = $isDeposit ? 'Dépôt espèces' : 'Paiement facture électricité';
                                    $subtitle = $isDeposit ? 'Transfert • Dépôt' : 'Paiement • Factures';
                            ?>
                                <div class="flex items-center justify-between p-4 <?= $bgClass ?> rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div class="<?= $isDeposit ? 'bg-green-100' : 'bg-red-100' ?> rounded-full p-2">
                                            <i class='bx <?= $iconClass ?> text-xl'></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800"><?= $title ?></div>
                                            <div class="text-sm text-gray-500"><?= $subtitle ?></div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold <?= $amountClass ?>">
                                            <?= $amountPrefix ?><?= number_format($transaction['montant'],0 , ',', ' ') ?> FCFA
                                        </div>
                                        <div class="text-sm text-gray-500"><?= $transaction['date'] ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>