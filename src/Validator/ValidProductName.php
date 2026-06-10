<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class ValidProductName extends Constraint
{
    public string $message = 'Le nom du produit contient un mot interdit : "{{ value }}".';
    public array $forbiddenWords = ['spam', 'pub'];

    // You can use #[HasNamedArguments] to make some constraint options required.
    // All configurable options must be passed to the constructor.
    public function __construct(
        public string $mode = 'strict',
        ?array $groups = null,
        mixed $payload = null,
        ?string $message = null,
        ?array $forbiddenWords = null,
    ) {

        if ($message !== null)
            $this->message = $message;

        if ($forbiddenWords !== null)
            $this->forbiddenWords = $forbiddenWords;

        parent::__construct([], $groups, $payload);
    }
}
