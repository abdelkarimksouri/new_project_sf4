<?php

namespace App\DataFixtures;

use App\Entity\Pharmacy;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;use Doctrine\Common\Persistence\ObjectManager;

class PharmacyFixtures extends Fixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 10; $i++) {

            $random = mt_rand(5, 15);

            $pharmacy = new Pharmacy();

            $pharmacy->setGeneratedName('name_'.$i);
            $pharmacy->setIsActive(true);
            $pharmacy->setIsNight(true);
            $pharmacy->setIsHoliday(true);
            $pharmacy->setUid('uid_'.$random);
            $pharmacy->setCreatedAt(new \DateTime());

            $this->addReference('pharmacy_'.$i, $pharmacy);

            $manager->persist($pharmacy);

        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
