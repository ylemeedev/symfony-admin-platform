<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ContactDto
{
    #[Assert\NotBlank()]
    public ?string $name = null;

    #[Assert\NotBlank()]
    public ?string $email = null;

    #[Assert\NotBlank()]
    public ?string $message = null;
}