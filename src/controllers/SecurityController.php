<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Upload;
use App\Core\Validator;
use App\Services\EnvoyerMessage;
use App\Services\SecurityService;
use App\Abstract\AbstractController;

class SecurityController extends AbstractController
{

    private SecurityService $securityService;

    public function __construct()
    {
        parent::__construct();
        $this->securityService = new SecurityService();
    }

    public function create(): void
    {
        // Réinitialiser le validateur pour éviter les erreurs résiduelles à l'affichage initial
        Validator::reset();
        
        // Récupérer les anciennes valeurs et erreurs s'il y en a
        $old = $this->session->get('old_input') ?? [];
        $errors = $this->session->get('flash_errors') ?? [];
        
        // Vider les valeurs en session après les avoir récupérées
        $this->session->set('old_input', null);
        $this->session->set('flash_errors', null);
        
        // Afficher le formulaire avec les données et erreurs récupérées
        $this->renderHtml('inscription', [
            'old' => $old,
            'errors' => $errors
            ]);
    }

    public function login()
    {
        // Si ce n'est pas une soumission de formulaire, afficher simplement le formulaire
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Récupérer les anciennes valeurs et erreurs s'il y en a
            $old = $this->session->get('old_input') ?? [];
            $errors = $this->session->get('flash_errors') ?? [];
            
            // Vider les valeurs en session après les avoir récupérées
            $this->session->set('old_input', null);
            $this->session->set('flash_errors', null);
            
            // Afficher le formulaire avec les données et erreurs récupérées
            $this->renderHtml('login', [
                'old' => $old,
                'errors' => $errors
            ]);
            return;
        }

        // Réinitialiser le validateur
        Validator::reset();
        
        $formData = [
            'login' => trim($_POST['login'] ?? ''),
            'password' => trim($_POST['password'] ?? '')
        ];
        
        // Validation des champs
        if (empty($formData['login'])) {
            Validator::addError('login', 'Le login est obligatoire');
        }
        
        if (empty($formData['password'])) {
            Validator::addError('password', 'Le mot de passe est obligatoire');
        }

        if (!Validator::isValid()) {
            $this->session->set('flash_errors', Validator::getErrors());
            $this->session->set('old_input', $formData);
            $this->renderHtml('login', [
                'old' => $formData,
                'errors' => Validator::getErrors()
            ]);
            return;
        }

        try {
            $user = $this->securityService->seConnecter($formData['login'], $formData['password']);
            
            if (!$user) {
                Validator::addError('global', 'Login ou mot de passe incorrect');
                $this->session->set('flash_errors', Validator::getErrors());
                $this->session->set('old_input', $formData);
                $this->renderHtml('login', [
                    'old' => $formData,
                    'errors' => Validator::getErrors()
                ]);
                return;
            }

            // Connexion réussie, stocker l'utilisateur en session
            $this->session->set('user', $user);
            $this->session->set('user_id', $user->getTelephone()); // Utiliser le téléphone comme identifiant
            $this->session->set('logged_in', true);
            
            // Assurer que la redirection fonctionne même si BASE_URL n'est pas défini
            $baseUrl = getenv('BASE_URL') ?: '';
            header('Location: ' . $baseUrl . '/accueil');
            exit;
            
        } catch (\Exception $e) {
            // En cas d'erreur technique, afficher un message d'erreur général
            error_log('Erreur lors de la connexion: ' . $e->getMessage());
            Validator::addError('global', 'Une erreur est survenue lors de la connexion. Veuillez réessayer.');
            $this->session->set('flash_errors', Validator::getErrors());
            $this->session->set('old_input', $formData);
            $this->renderHtml('login', [
                'old' => $formData, 
                'errors' => Validator::getErrors()
            ]);
            return;
        }
    }

    public function store(): void 
    {
        // Si ce n'est pas une soumission de formulaire, rediriger vers la page d'inscription
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->renderHtml('inscription');
            return;
        }
        
        // Réinitialiser le validateur pour éviter les erreurs résiduelles
        Validator::reset();
        
        $formData = [
            'login' => trim($_POST['login'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'nom' => trim($_POST['nom'] ?? ''),
            'adresse' => trim($_POST['adresse'] ?? ''),
            'numero_identite' => trim($_POST['numeroIdentite'] ?? ''),
            'telephone' => trim($_POST['telephone'] ?? ''),
            'typePersonne' => 'client',
            'photoRecto' => null,
            'photoVerso' => null
        ];
        
        // Validation des champs obligatoires uniquement lors de la soumission du formulaire
        if (empty($formData['login'])) {
            Validator::addError('login', 'Le login est obligatoire');
        }
        
        if (empty($formData['password'])) {
            Validator::addError('password', 'Le mot de passe est obligatoire');
        }
        
        if (empty($formData['prenom'])) {
            Validator::addError('prenom', 'Le prénom est obligatoire');
        }
        
        if (empty($formData['nom'])) {
            Validator::addError('nom', 'Le nom est obligatoire');
        }
        
        if (empty($formData['adresse'])) {
            Validator::addError('adresse', 'L\'adresse est obligatoire');
        }
        
        if (empty($formData['numero_identite'])) {
            Validator::addError('numeroIdentite', 'Le numéro CNI est obligatoire');
        }
        
        if (empty($formData['telephone'])) {
            Validator::addError('telephone', 'Le téléphone est obligatoire');
        } elseif (!preg_match('/^\d{9,15}$/', $formData['telephone'])) {
            Validator::addError('telephone', 'Format de téléphone invalide');
        }
        
        // Validation des photos
        $photoRecto = Upload::save($_FILES['photorecto'] ?? null, 'uploads/cni');
        $photoVerso = Upload::save($_FILES['photoverso'] ?? null, 'uploads/cni');
        
        if (!$photoRecto) {
            Validator::addError('photorecto', 'La photo recto de la CNI est obligatoire');
        } else {
            $formData['photoRecto'] = $photoRecto;
        }
        
        if (!$photoVerso) {
            Validator::addError('photoverso', 'La photo verso de la CNI est obligatoire');
        } else {
            $formData['photoVerso'] = $photoVerso;
        }
        
        // Vérification des validations
        if (!Validator::isValid()) {
            // Stockage des erreurs et des données en session
            $this->session->set('flash_errors', Validator::getErrors());
            $this->session->set('old_input', $formData);
            $this->renderHtml('inscription');
            return;
        }

        $app = App::getInstance();
        $securityService = $app->getDependency('security');


        if ($securityService->inscrire($formData)) {
            try {
                EnvoyerMessage::envoyerConfirmationInscription(
                    $formData['telephone'],
                    $formData['nom'],
                    $formData['prenom']
                );
                error_log("SMS de confirmation d'inscription envoyé à " . $formData['telephone']);
            } catch (\Exception $e) {
                error_log("Erreur lors de l'envoi du SMS de confirmation: " . $e->getMessage());
            }
            
            $this->session->set('flash_success', 'Inscription réussie !');
            header('Location: ' . getenv('BASE_URL') . '/login');
            exit();
        }

        $this->session->set('flash_errors', Validator::getErrors());
        $this->session->set('old_input', $formData);
        $this->renderHtml('inscription');
    }

    public function show():void {}

    public function update():void {}

    public function edit():void {}
    public function destroy():void {}

    public function index():void
    {
        // Réinitialiser le validateur pour éviter les erreurs résiduelles à l'affichage initial
        Validator::reset();
        
        // Afficher simplement le formulaire de login sans erreurs
        $this->renderHtml('login');
    }

    

    public function logout()
    {
        $this->session->destroy();
        header('Location: /');
    }
}
