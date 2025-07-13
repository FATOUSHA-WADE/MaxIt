<?php

namespace App\Controllers;

use App\Core\App;
use App\Abstract\AbstractController;

class AcceuilController extends AbstractController
{
    public function index():void
    {
        try {
            $app = App::getInstance();
            $session = $app->getDependency('session');
            
            $user = $session->get('user');
            
            if (!$user) {
                header('Location: /login');
                exit;
            }
            
            // Récupération du téléphone de l'utilisateur (qui est notre clé primaire)
            $telephone = $session->get('user_id');
            
            // Si user_id n'existe pas dans la session, on essaie de le récupérer depuis l'objet user
            if (!$telephone && $user && method_exists($user, 'getTelephone')) {
                $telephone = $user->getTelephone();
                
                // Log pour le débogage
                error_log('Téléphone récupéré de l\'objet user: ' . ($telephone ?: 'non disponible'));
                
                // Stocke le téléphone dans la session pour les prochaines utilisations
                if ($telephone) {
                    $session->set('user_id', $telephone);
                }
            }
            
            // Vérification que le téléphone est bien défini
            if (empty($telephone)) {
                error_log('Erreur: Téléphone utilisateur non trouvé dans la session ou l\'objet utilisateur');
                header('Location: /login');
                exit;
            }
            
            // S'assurer que le téléphone est bien une chaîne de caractères
            $telephone = (string) $telephone;
            
            $compteRepository = $app->getDependency('compteRepository');
            $transactionRepository = $app->getDependency('transactionRepository');
            
            error_log('Type de transactionRepository: ' . gettype($transactionRepository));
            if (is_object($transactionRepository)) {
                error_log('Classe de transactionRepository: ' . get_class($transactionRepository));
            }
            
            error_log('Téléphone utilisateur utilisé: ' . $telephone);
            
            $comptes = [];
            try {
                $comptes = $compteRepository->findByPersonne($telephone);
                error_log('Nombre de comptes trouvés: ' . count($comptes));
            } catch (\Exception $e) {
                error_log('Erreur lors de la récupération des comptes: ' . $e->getMessage());
                error_log($e->getTraceAsString());
                // Continuer l'exécution avec un tableau vide
            }
            
            $transactions = [];
            try {
                if ($transactionRepository && method_exists($transactionRepository, 'findRecentByPersonne')) {
                    $transactions = $transactionRepository->findRecentByPersonne($telephone, 10);
                    error_log('Nombre de transactions trouvées: ' . count($transactions));
                } else {
                    error_log('TransactionRepository ou méthode findRecentByPersonne non disponible');
                }
            } catch (\Exception $e) {
                error_log('Erreur lors de la récupération des transactions: ' . $e->getMessage());
                error_log($e->getTraceAsString());
                // Continuer l'exécution avec un tableau vide
            }
            
            $this->renderHtml('accueil', [
                'user' => $user,
                'comptes' => $comptes ?? [],
                'transactions' => $transactions
            ]);
        } catch (\Exception $e) {
            error_log("Erreur dans AcceuilController::index : " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            
            $this->renderHtml('error', [
                'message' => 'Une erreur est survenue lors du chargement de la page d\'accueil.'
            ]);
        }
    }
    
    public function create(): void {}
    public function store(): void {}
    public function update(): void {}
    public function show(): void {}
    public function edit(): void {}
    public function destroy() { return null; }
}