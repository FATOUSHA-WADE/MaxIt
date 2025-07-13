<?php
namespace App\Entity;
use App\Entity\TypePersonne;

require_once __DIR__ . '/Enums.php';

class Compte
{
    private string $telephone;
    private float $solde;
    private PersonneEntity $personne;
    private string $typeCompte;

    private array $transactions = [];

    public function __construct(string $telephone, float $solde, PersonneEntity $personne, string $typeCompte)
    {
        if (!in_array($typeCompte, [TypeCompte::PRINCIPAL, TypeCompte::SECONDAIRE])) {
            throw new \InvalidArgumentException("Type de compte invalide");
        }
        $this->telephone = $telephone;
        $this->solde = $solde;
        $this->personne = $personne;
        $this->typeCompte = $typeCompte;
    }

    public function addTransaction(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    public function getPersonne(): PersonneEntity
    {
        return $this->personne;
    }

    public function setPersonne(PersonneEntity $personne): void
    {
        $this->personne = $personne;
    }

    public function getTypeCompte(): string
    {
        return $this->typeCompte;
    }

    public function setTypeCompte(string $type): void
    {
        $this->typeCompte = $type;
    }
}
