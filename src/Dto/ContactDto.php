<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ContactDto
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 3, max: 50)]
    public string $name = '';

    #[Assert\NotBlank()]
    #[Assert\Email(
        message: 'L\'adresse e-mail {{ value }} n\'est pas valide.'
    )]
    public string $email = '';

    #[Assert\NotBlank()]
    public string $service = '';

    #[Assert\NotBlank()]
    #[Assert\Length(min: 3, max: 500)]
    public string $message = '';
}
