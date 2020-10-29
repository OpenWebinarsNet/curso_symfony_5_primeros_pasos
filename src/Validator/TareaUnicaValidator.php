<?php

namespace App\Validator;

use App\Repository\TareaRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TareaUnicaValidator extends ConstraintValidator
{

    private $tareaRepository;

    public function __construct(TareaRepository $tareaRepository)
    {
        $this->tareaRepository = $tareaRepository;
    }

    public function validate($tarea, Constraint $constraint)
    {
        $descripcion = $tarea->getDescripcion();
        if (null === $descripcion || '' === $descripcion) {
            return;
        }

        $tareaCondescripcionIgual = $this->tareaRepository->buscarTareaPorDescripcion($descripcion);
        if (null !== $tareaCondescripcionIgual && $tarea->getId() !== $tareaCondescripcionIgual->getId()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $descripcion)
                ->addViolation();
        }
    }
}
