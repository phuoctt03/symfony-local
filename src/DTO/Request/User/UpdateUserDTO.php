<?php

namespace App\DTO\Request\User;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 20)]
    public ?string $username = null;

    #[Assert\Length(min: 6)]
    public ?string $password = null;

    #[Assert\Email]
    public ?string $email = null;

    #[Assert\Length(max: 15)]
    public ?string $phone = null;

    public ?string $address = null;

    public ?string $role = null;

    public function __construct(?string $username, ?string $password, ?string $email, ?string $phone, ?string $address, ?string $role)
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->role = $role;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;
        return $this;
    }
}
