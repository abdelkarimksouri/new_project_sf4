<?php

namespace App\DataFixtures;

use App\Entity\Drug;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;use Doctrine\Common\Persistence\ObjectManager;

class DrugFixtures extends Fixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i < 10; $i++) {
            $random = mt_rand(5, 15);
            $drug = new Drug();

            $drug->setBareCode('1012'.$i.$random);
            $drug->setDrugName('drugName_'.$i);
            $drug->setDescription('Description_'.$i);
            $drug->setPrice($random.'.'.$i);
            $drug->setExpiredAt(new \DateTime());
            $drug->setCreatedAt(new \DateTime());
            $drug->setIsDeleted(false);
            $drug->setPharmacy($this->getReference('pharmacy_'.$i));

            $manager->persist($drug);

        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}