<?php

namespace App\DataFixtures;

use App\Entity\Category;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $immobilier = new Category();
        $immobilier->setName('Immobilier');
        $immobilier->setSlug('immobilier');
        $immobilier->setPosition(1);
        $immobilier->setIsActive(true);

        $now = new DateTimeImmutable();

        $immobilier->setCreatedAt($now);
        $immobilier->setUpdatedAt($now);

        $vehicules = new Category();
        $vehicules->setName('Véhicules');
        $vehicules->setSlug('vehicules');
        $vehicules->setPosition(2);
        $vehicules->setIsActive(true);

        $vehicules->setCreatedAt($now);
        $vehicules->setUpdatedAt($now);

        $manager->persist($immobilier);
        $manager->persist($vehicules);
        $manager->flush();
    }
}
