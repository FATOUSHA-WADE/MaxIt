<?php


namespace App\Services;

use App\Repository\CompteRepository;

class CompteService
{
    private CompteRepository $compteRepository;

    public function __construct()
    {
        $this->compteRepository = new CompteRepository();
    }

    public function getSolde(string $telephone): float
    {
        $compte = $this->compteRepository->findByPersonne($telephone);
        return $compte ? $compte['solde'] : 0.0;
    }
}