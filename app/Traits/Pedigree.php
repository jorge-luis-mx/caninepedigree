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
    public function findPedigree(?Dog $dog, int $generation = 1, int $maxGenerations = 4): ?array
    {
        if (!$dog || $generation > $maxGenerations) {
            return null;
        }

        return [
            'dog' => [
                'id' => md5($dog->dog_id),
                'name' => $dog->name,
                'breed' => $dog->breed,
                'color' => $dog->color,
                'sex' => $dog->sex,
                'date' => $dog->birthdate,
                'status' => $dog->status,
                // Información del dueño (propietario)
                'owner' => $dog->currentOwner ? [
                    'id' => $dog->currentOwner->profile_id,  // Asumimos que currentOwner es la relación al modelo UserProfile
                    'name' => $dog->currentOwner->name,  // Nombre del propietario
                    'email' => $dog->currentOwner->email,  // Correo electrónico del propietario (o cualquier otro campo necesario)
                ] : null,  // Si no existe un dueño, se retorna null

            ],
            // Llamada recursiva a los padres del perro (sire y dam usando el metodos de ralation ship)
            'sire' => $this->findPedigree($dog->sire, $generation + 1, $maxGenerations),
            'dam' => $this->findPedigree($dog->dam, $generation + 1, $maxGenerations),
        ];
    }
}
