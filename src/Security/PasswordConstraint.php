<?php

namespace App\Security;

use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordConstraint extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
        new Assert\NotBlank([
            'message' => 'Le champ ne doit pas être vide'
        ]),
        new Assert\Type([
            'type' => 'string',
            'message' => 'Vous devez entrer du texte'
        ]),
        new Assert\Length([
            'min' => 8,
            'max' => 255,
            'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères.',
            'maxMessage' => 'Le mot de passe ne doit pas faire plus de {{ limit }} caractères.',
        ]),
        //At elast one digit
        new Assert\Regex([
            'pattern' => '/\d+/i',
            'message' => 'Le mot de passe doit contenir au moins un chiffre.'
        ]),
        //At elast one special char from the list
        new Assert\Regex([
            'pattern' => '/[#?!€@$%^&*-]+/i',
            'message' => 'Le mot de passe doit contenir au moins un caractère spécial comme #?!€@$%^&*-'
        ]),
        ];
    }
}
