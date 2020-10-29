<?php

namespace App\Service;

use App\Entity\Tarea;
use App\Repository\TareaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TareaManager
{
    private $em;
    private $tareaRepository;
    private $validator;

    public function __construct(TareaRepository $tareaRepository, ValidatorInterface $validatorInterface, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->tareaRepository = $tareaRepository;
        $this->validator = $validatorInterface;
    }

    public function crear(Tarea $tarea): void
    {
        $this->em->persist($tarea);
        $this->em->flush();
    }

    public function editar(Tarea $tarea): void
    {
        $this->em->flush();
    }

    public function eliminar(Tarea $tarea): void
    {
        $this->em->remove($tarea);
        $this->em->flush();
    }

    public function validar(Tarea $tarea): ConstraintViolationList
    {
        $errores = $this->validator->validate($tarea);
        /*if (empty($tarea->getDescripcion()))
            $errores[] = "Campo 'descripción' obligatorio";

        $tareaCondescripcionIgual = $this->tareaRepository->buscarTareaPorDescripcion($tarea->getDescripcion());
        if (null !== $tareaCondescripcionIgual && $tarea->getId() !== $tareaCondescripcionIgual->getId()) {
            $errores[] = "Descripción repetida";
        }*/
        
        return $errores;
    }
}
