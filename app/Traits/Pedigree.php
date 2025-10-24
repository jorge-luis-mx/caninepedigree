<?php

namespace App\Traits;

use App\Models\Dog;

trait Pedigree
{
    /**
     * Genera el árbol genealógico hasta 4 generaciones.
     *
     * @param Dog|null $dog
     * @param int $generation
     * @param int $maxGenerations
     * @return array|null
     */
    public function findPedigree(?Dog $dog, int $generation = 1, int $maxGenerations = 5): ?array
    {
        if (!$dog || $generation > $maxGenerations) {
            return null;
        }

        return [
            'dog' => [
                'id' => md5($dog->dog_id),
                'number'=>$dog->reg_no,
                'name' => $dog->name,
                'breed' => $dog->breed,
                'color' => $dog->color,
                'sex' => $dog->sex,
                'url'=>$dog->url,
                'birthdate' => $dog->birthdate,
                'date' => $dog->created_at,
                'status' => $dog->status,
                // Información del dueño (propietario)
                'owner' => $dog->currentOwner ? [
                    'id' => $dog->currentOwner->profile_id,  // Asumimos que currentOwner es la relación al modelo UserProfile
                    'name' => $dog->currentOwner->first_name,  // Nombre del propietario
                    'last_name'=>$dog->currentOwner->last_name,
                    'kennel_name'=>$dog->currentOwner->kennel_name,
                    'middle_name'=>$dog->currentOwner->middle_name,
                    'phone'=>$dog->currentOwner->phone,
                    'address'=>$dog->currentOwner->address,
                    'email' => $dog->currentOwner->email,  // Correo electrónico del propietario (o cualquier otro campo necesario)
                ] : null,  // Si no existe un dueño, se retorna null

            ],
            // Llamada recursiva a los padres del perro (sire y dam usando el metodos de ralation ship)
            'sire' => $this->findPedigree($dog->sire, $generation + 1, $maxGenerations),
            'dam' => $this->findPedigree($dog->dam, $generation + 1, $maxGenerations),
        ];
    }
}
