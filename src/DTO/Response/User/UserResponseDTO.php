<?php

namespace App\DTO\Response\User;

use App\Entity\User;

class UserResponseDTO
{
    public int $id;
    public string $username;
    public string $email;
    public ?string $phone;
    public ?string $address;
    public string $role;

    // Constructor to initialize DTO from a User entity
    public function __construct(User $user)
    {
        $this->id = $user->getId(); // assuming getId() exists
        $this->username = $user->getUsername(); // assuming getUsername() exists
        $this->email = $user->getEmail(); // assuming getEmail() exists
        $this->phone = $user->getPhone(); // assuming getPhone() exists
        $this->address = $user->getAddress(); // assuming getAddress() exists
        $this->role = $user->getRole()->value; // assuming getRole() returns an enum and has a 'value' property
    }

    // Convert DTO to array format (for easy JSON response)
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'role' => $this->role,
        ];
    }
}
