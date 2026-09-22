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
        $realEstate = new Category();

        $realEstate->setName('Immobilier');
        $realEstate->setSlug('immobilier');
        $realEstate->setPosition(1);
        $realEstate->setIsActive(true);

        $now = new DateTimeImmutable();

        $realEstate->setCreatedAt($now);
        $realEstate->setUpdatedAt($now);

        $vehicles = new Category();
        $vehicles->setName('Véhicules');
        $vehicles->setSlug('vehicles');
        $vehicles->setPosition(2);
        $vehicles->setIsActive(true);

        $vehicles->setCreatedAt($now);
        $vehicles->setUpdatedAt($now);

        $manager->persist($realEstate);
        $this->addReference('category_immobilier', $realEstate);

        $manager->persist($vehicles);
        $this->addReference('category_vehicules', $vehicles);

        $manager->flush();
    }
}
