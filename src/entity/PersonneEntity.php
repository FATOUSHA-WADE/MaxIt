<?php

namespace App\Entity;

use App\Abstract\AbstractEntity;

class PersonneEntity extends AbstractEntity implements \Serializable
{
    protected ?int $id = null;
    protected string $nom = '';
    protected string $prenom = '';
    protected string $telephone = '';
    protected string $adresse = '';
    protected string $typePersonne = 'client';
    protected string $numeroIdentite = '';
    protected string $login = '';
    protected string $password = '';
    protected ?string $photoRecto = null;
    protected ?string $photoVerso = null;
    
    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }
    

    public function serialize()
    {
        return serialize([
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'telephone' => $this->telephone,
            'adresse' => $this->adresse,
            'typePersonne' => $this->typePersonne,
            'numeroIdentite' => $this->numeroIdentite,
            'login' => $this->login,
            'password' => $this->password,
            'photoRecto' => $this->photoRecto,
            'photoVerso' => $this->photoVerso
        ]);
    }
    

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->id = $data['id'] ?? null;
        $this->nom = $data['nom'] ?? '';
        $this->prenom = $data['prenom'] ?? '';
        $this->telephone = $data['telephone'] ?? '';
        $this->adresse = $data['adresse'] ?? '';
        $this->typePersonne = $data['typePersonne'] ?? 'client';
        $this->numeroIdentite = $data['numeroIdentite'] ?? '';
        $this->login = $data['login'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->photoRecto = $data['photoRecto'] ?? null;
        $this->photoVerso = $data['photoVerso'] ?? null;
    }
    

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'telephone' => $this->telephone,
            'adresse' => $this->adresse,
            'typePersonne' => $this->typePersonne,
            'numeroIdentite' => $this->numeroIdentite,
            'login' => $this->login,
            'password' => $this->password,
            'photoRecto' => $this->photoRecto,
            'photoVerso' => $this->photoVerso
        ];
    }
    

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->nom = $data['nom'] ?? '';
        $this->prenom = $data['prenom'] ?? '';
        $this->telephone = $data['telephone'] ?? '';
        $this->adresse = $data['adresse'] ?? '';
        $this->typePersonne = $data['typePersonne'] ?? 'client';
        $this->numeroIdentite = $data['numeroIdentite'] ?? '';
        $this->login = $data['login'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->photoRecto = $data['photoRecto'] ?? null;
        $this->photoVerso = $data['photoVerso'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'telephone' => $this->telephone,
            'adresse' => $this->adresse,
            'type_personne' => $this->typePersonne,
            'numero_identite' => $this->numeroIdentite,
            'login' => $this->login,
            'password' => $this->password,
            'photo_recto' => $this->photoRecto,
            'photo_verso' => $this->photoVerso
        ];
    }

    public static function toObject(array $data): self
    {
        $personne = new self();
        
        $mapping = [
            'id' => 'id',
            'nom' => 'nom',
            'prenom' => 'prenom',
            'telephone' => 'telephone',
            'adresse' => 'adresse',
            'type_personne' => 'typePersonne',
            'numero_identite' => 'numeroIdentite',
            'login' => 'login',
            'password' => 'password',
            'photo_recto' => 'photoRecto',
            'photo_verso' => 'photoVerso'
        ];
        
        $transformedData = [];
        foreach ($data as $key => $value) {
            if (isset($mapping[$key])) {
                $transformedData[$mapping[$key]] = $value;
            }
        }
        
        return $personne->hydrate($transformedData);
    }
    
    // Getters et setters
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getNom(): string
    {
        return $this->nom;
    }
    
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }
    
    public function getPrenom(): string
    {
        return $this->prenom;
    }
    
    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }
    
    public function getTelephone(): string
    {
        return $this->telephone;
    }
    
    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }
    
    public function getAdresse(): string
    {
        return $this->adresse;
    }
    
    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }
    
    public function getTypePersonne(): string
    {
        return $this->typePersonne;
    }
    
    public function setTypePersonne(string $typePersonne): self
    {
        $this->typePersonne = $typePersonne;
        return $this;
    }
}