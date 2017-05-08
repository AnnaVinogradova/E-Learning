<?php

namespace ELearning\CompanyPortalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Elearning\CompanyPortalBundle\Entity\Category;

class LoadCategoryData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $category1 = new Category();
        $category1->setName('Art');
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setName('Business');
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setName('Computer science');
        $manager->persist($category3);

        $category4 = new Category();
        $category4->setName('Math and logic');
        $manager->persist($category4);

        $category5 = new Category();
        $category5->setName('Physical science');
        $manager->persist($category5);

        $category6 = new Category();
        $category6->setName('Social science');
        $manager->persist($category6);

        $category7 = new Category();
        $category7->setName('Language learning');
        $manager->persist($category7);

        $category8 = new Category();
        $category8->setName('Life science');
        $manager->persist($category8);

        $manager->flush();
    }
}
