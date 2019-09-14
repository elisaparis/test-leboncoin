<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\MetaCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cat1 = new Category();
        $cat1->setName('Emploi');

        $cat2 = new Category();
        $cat2->setName('Automobile');

        $cat3 = new Category();
        $cat3->setName('Immobilier');

        $manager->persist($cat1);
        $manager->persist($cat2);
        $manager->persist($cat3);
        $manager->flush();
    }
}
