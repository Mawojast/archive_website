<?php

namespace App\Validator;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ArchiveDateAreaValidator checks whether the received form date, 
 * which is in class object ArchiveSearchDTO, is in the permitted range.
 */
class ArchiveDateAreaValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var ArchiveDateArea $constraint */

        if (!$constraint instanceof ArchiveDateArea) {
            throw new UnexpectedTypeException($constraint, ArchiveDateArea::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        // Using ArchiveSearchDTO object to check the allowed date value range
        $archiveSearchDTO = $this->context->getObject();
        if($value >= $archiveSearchDTO->minDate && $value <= $archiveSearchDTO->maxDate) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value->format('Y-m-d'))
            ->addViolation();
    }
}
