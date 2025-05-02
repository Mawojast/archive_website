<?php
declare(strict_types=1);
// src/ValueResolver/IdentifierValueResolver.php
namespace App\ValueResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;

/**
 * Class ArchiveNameValueResolver ensures 
 * that the /{archive} attribute is passed in lowercase
 */
#[AsTargetedValueResolver('archive')]
class ArchiveNameValueResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $value = $request->attributes->get($argument->getName());
        return [strtolower($value)];
    }
}
