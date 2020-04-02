<?php

namespace App\DataFixtures;

use App\Entity\Media;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;use Doctrine\Common\Persistence\ObjectManager;

class MediaFixtures extends Fixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i < 10; $i++) {
            $random = mt_rand(5, 15);
            $media = new Media();

            $media->setFilePath('filePath_'.$i);
            $media->setFileType('fileType_'.$i);
            $media->setFileSize($random);
            $media->setIsTreated(false);
            $media->setIsTreated(true);
            $media->setIsDeleted(false);
            $media->setUploadedAt(new \DateTime());
            $media->setLabel('label_'.$i);
            $media->setPharmacy($this->getReference('pharmacy_'.$i));

            $manager->persist($media);

        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}