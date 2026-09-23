<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Listing;
use App\Entity\User;
use App\Enum\ListingStatus;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ListingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        $immobilier = $this->getReference('category_immobilier', Category::class);
        $vehicles = $this->getReference('category_vehicules', Category::class);
        $seller1 = $this->getReference('user_1', User::class);
        $seller2 = $this->getReference('user_2', User::class);

        $listing1 = new Listing();
        $listing1->setTitle('Appartement T3 lumineux');
        $listing1->setDescription('Bel appartement proche du centre-ville, 65 metres carres, 2 chambres.');
        $listing1->setPrice(1200);
        $listing1->setCity('Paris');
        $listing1->setStatus(ListingStatus::PUBLISHED);
        $listing1->setSeller($seller1);
        $listing1->setCategory($immobilier);
        $listing1->setCreatedAt($now);
        $listing1->setUpdatedAt($now);
        $listing1->setPublishedAt($now);

        $listing2 = new Listing();
        $listing2->setTitle('Peugeot 208 2019');
        $listing2->setDescription('Voiture citadine, bon etat, 45 000 km.');
        $listing2->setPrice(9500);
        $listing2->setCity('Lyon');
        $listing2->setStatus(ListingStatus::PUBLISHED);
        $listing2->setSeller($seller2);
        $listing2->setCategory($vehicles);
        $listing2->setCreatedAt($now);
        $listing2->setUpdatedAt($now);
        $listing2->setPublishedAt($now);

        $listing3 = new Listing();
        $listing3->setTitle('Maison familiale avec jardin');
        $listing3->setDescription('Maison 5 pieces avec jardin de 300 metres carres.');
        $listing3->setPrice(280000);
        $listing3->setCity('Bordeaux');
        $listing3->setStatus(ListingStatus::DRAFT);
        $listing3->setSeller($seller1);
        $listing3->setCategory($immobilier);
        $listing3->setCreatedAt($now);
        $listing3->setUpdatedAt($now);

        $manager->persist($listing1);
        $manager->persist($listing2);
        $manager->persist($listing3);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
