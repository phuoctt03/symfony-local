<?php

namespace App\Service;

use App\DTO\Request\User\CreateUserDTO;
use App\DTO\Request\User\UpdateUserDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Enum\UserRole;
class UserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordEncoder
    ) {}

    public function findByUsername(string $username): ?User
    {
        // Tìm user theo username
        return $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
    }

    public function findByEmail(string $email): ?User
    {
        // Tìm user theo email
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    public function createUser(CreateUserDTO $dto): User
    {
        // Tạo mới user
        $user = new User();
        $user->setUsername($dto->getUsername());
        $user->setEmail($dto->getEmail());
        $user->setPassword($this->passwordEncoder->hashPassword($user, $dto->getPassword()));
        $user->setRole(UserRole::from($dto->getRole()));
        $user->setPhone($dto->getPhone());
        $user->setAddress($dto->getAddress());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function getAllUsers(): array
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    public function getUserById(int $id): ?User
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    public function getUserByUsername(string $username): ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
    }

    public function isPasswordValid(User $user, string $password): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $password);
    }

    public function updateUser(int $id, UpdateUserDTO $dto): User
    {
        $user = $this->getUserById($id);
        if (!$user) {
            throw new \Exception('User not found');
        }

        $user->setUsername($dto->getUsername());
        $user->setEmail($dto->getEmail());
        if ($dto->getPassword()) {
            $user->setPassword($this->passwordEncoder->hashPassword($user, $dto->getPassword()));
        }
        $user->setRole(UserRole::from($dto->getRole()));
        $user->setPhone($dto->getPhone());
        $user->setAddress($dto->getAddress());

        $this->entityManager->flush();

        return $user;
    }

    public function deleteUser(int $id): void
    {
        $user = $this->getUserById($id);
        if ($user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }
    }
}
