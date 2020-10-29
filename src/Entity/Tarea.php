<?php

namespace App\Entity;

use App\Repository\TareaRepository;
use App\Validator as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @AppAssert\TareaUnica
 * @ORM\Entity(repositoryClass=TareaRepository::class) 
 */
class Tarea
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="El campo descripción no puede estar vacío")
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }
}
