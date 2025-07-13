<?php

namespace App\Entity;

class TransactionEntity
{
    private int $id;
    private float $montant;
    private string $compteTelephone;
    private string $type;
    private string $date;
    
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->montant = $data['montant'];
        $this->compteTelephone = $data['compte_telephone'];
        $this->type = $data['type'];
        $this->date = $data['date'];
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getMontant(): float
    {
        return $this->montant;
    }
    
    public function getCompteTelephone(): string
    {
        return $this->compteTelephone;
    }
    
    public function getType(): string
    {
        return $this->type;
    }
    
    public function getDate(): string
    {
        return $this->date;
    }
    
    public function getFormattedDate(string $format = 'd/m/Y'): string
    {
        return date($format, strtotime($this->date));
    }
    
    public function getFormattedMontant(string $separator = ' ', string $decimalSeparator = ','): string
    {
        return number_format($this->montant, 2, $decimalSeparator, $separator);
    }
}