<?php

declare(strict_types=1);

namespace App\Application\Validator;

use App\Application\ValueObject\ValidationInterface;

interface ValidatorInterface
{
    public function validate(?ValidationInterface $data = null): void;
}
