<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Services\User\Enums\RolesEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail($_ENV['ADMIN_EMAIL'] ?? 'admin@example.com');
        $admin->setRoles([RolesEnum::ROLE_ADMIN->value]);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, $_ENV['ADMIN_PASSWORD'] ?? 'admin123')
        );

        $manager->persist($admin);
        $manager->flush();
    }
}
