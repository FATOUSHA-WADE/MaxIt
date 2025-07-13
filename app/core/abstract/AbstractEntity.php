<?php 

namespace App\Abstract;

abstract class AbstractEntity
{

    public function __get(string $name)
    {
        $getter = 'get' . ucfirst($name);
        
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }
        
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        
        throw new \Exception("La propriété '$name' n'existe pas dans l'entité " . get_class($this));
    }


    public function __set(string $name, $value): void
    {
        $setter = 'set' . ucfirst($name);
        
        if (method_exists($this, $setter)) {
            $this->$setter($value);
            return;
        }
        
        if (property_exists($this, $name)) {
            $this->$name = $value;
            return;
        }
        
        throw new \Exception("La propriété '$name' n'existe pas dans l'entité " . get_class($this));
    }


    public function __isset(string $name): bool
    {
        $getter = 'get' . ucfirst($name);
        
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        }
        
        return property_exists($this, $name) && $this->$name !== null;
    }


    abstract public function toArray(): array;

    abstract public static function toObject(array $data);


    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
    

    public function hydrate(array $data): self
    {
        foreach ($data as $key => $value) {
            try {
                $this->__set($key, $value);
            } catch (\Exception $e) {

                continue;
            }
        }
        
        return $this;
    }
}
