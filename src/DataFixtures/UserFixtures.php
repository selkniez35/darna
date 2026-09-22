<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $user->setEmail('user@example.com');
        $user->setPassword('password');
        $user->setRoles(['ROLE_USER']);
        $user->setDisplayName('John Doe');
        $user->setIsActive(true);
        $user->setIsVerified(true);

        $now = new DateTimeImmutable();

        $user->setUpdatedAt($now);
        $user->setCreatedAt($now);

        $manager->persist($user);
        $manager->flush();
    }
}
