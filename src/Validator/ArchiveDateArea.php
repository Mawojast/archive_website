<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Attribute\HasNamedArguments;


/**
 * Constraint that ensures form date values are within the allowed range.
 */
#[\Attribute]
class ArchiveDateArea extends Constraint
{
    public string $message = '';

    #[HasNamedArguments]
    public function __construct(
        ?string $message = null, 
        ?array $groups = null, 
        $payload = null
    ) {
        parent::__construct([], $groups, $payload);  
        $this->message = $message ?? $this->message;
    }
}
