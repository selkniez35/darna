<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        $seller1 = new User();
        $seller1->setEmail('alice@example.com');
        $seller1->setPassword('password');
        $seller1->setRoles(['ROLE_USER']);
        $seller1->setDisplayName('Alice Martin');
        $seller1->setIsActive(true);
        $seller1->setIsVerified(true);
        $seller1->setCreatedAt($now);
        $seller1->setUpdatedAt($now);

        $seller2 = new User();
        $seller2->setEmail('bob@example.com');
        $seller2->setPassword('password');
        $seller2->setRoles(['ROLE_USER']);
        $seller2->setDisplayName('Bob Durand');
        $seller2->setIsActive(true);
        $seller2->setIsVerified(true);
        $seller2->setCreatedAt($now);
        $seller2->setUpdatedAt($now);

        $manager->persist($seller1);
        $this->addReference('user_1', $seller1);

        $manager->persist($seller2);
        $this->addReference('user_2', $seller2);

        $manager->flush();
    }
}
