<?php

namespace App\Entity;

class Role
{
    private $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    /**
     * Returns a string representation of the role.
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    public function __toString(): string
    {
        return $this->role;
    }
}
