<?php
namespace App\Abstract;
use App\Core\Session;
abstract class AbstractController{

            
    protected ?string $layout;
    protected Session $session;


    public function __construct($layout = null)
    {
        $this->session = Session::getInstance();
        $this->layout = $layout ?? dirname(__DIR__, 3) . '/template/layout/base.layout.php';
    }

    abstract public  function index(): void;
    abstract public function create(): void;
    abstract public function store(): void;
    abstract public function update(): void;
    abstract public function show(): void;
    abstract public function edit(): void;
    abstract public function destroy();

    public function renderHtml(string $view, $data = [])
    {
        $layoutPath = dirname(__DIR__, 3) . '/templates/layout/partials/sidebar.layout.php';   
    if (in_array($view, ['accueil', 'inscription', 'ajouterCompteSecond'])) {
        require_once $layoutPath;
    }
        extract($data);
        ob_start();
    require_once dirname(__DIR__, 3) . '/templates/' . $view . '.html.php';
        $contentForLayout = ob_get_clean();
        echo $contentForLayout;
    }

    public function requireAuth(): void
    {
        session_start();
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }

    public function deconnexion(): void
    {
        session_destroy();
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }
}