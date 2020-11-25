<?php

namespace App\Core\Entities\User;

use App\Exceptions\DomainException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Name
{
    /**
     * @ORM\Column(type="string",  name="first_name")
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string",  name="last_name")
     */
    private string $lastName;



    public function __construct(string $firstName, string $lastName)
    {
        if (empty($firstName)) {
            throw new DomainException('First name must not be empty');
        }
        if (empty($lastName)) {
            throw new DomainException('Last name must not be empty');
        }

        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }


    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
