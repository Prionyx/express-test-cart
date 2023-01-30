<?php

namespace App\Validators;

use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class ProductRequestValidator
{
    public function validate($value): void
    {
        $constraints = new Assert\Collection([
            'type' => [
                new Assert\Optional(new Assert\Regex([
                    'pattern' => '/^\d*$/i',
                    'match' => true,
                    'message' => 'Type id must be integer'
                ]))
            ]
        ]);

        $validator = Validation::createValidator();
        $violations = $validator->validate($value, $constraints);
        if ($violations->count() !== 0) {
            throw new InvalidParameterException((string) $violations);
        }
    }
}
