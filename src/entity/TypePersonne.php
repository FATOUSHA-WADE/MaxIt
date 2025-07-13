<?php

namespace App\Entity;

abstract class TypePersonne {
    const CLIENT = 'client';
    const COMMERCIAL = 'commercial';
}

abstract class TypeCompte {
    const PRINCIPAL = 'principal';
    const SECONDAIRE = 'secondaire';
}

abstract class TypeTransaction {
    const DEPOT = 'depot';
    const RETRAIT = 'retrait';
    const PAIEMENT = 'paiement';
}