<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Country;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CountryFixtures  extends Fixture implements OrderedFixtureInterface
{

//    /**
//     * @var ContainerInterface
//     */
//    private $container;
//    /**
//     * @var string
//     */
//    private $file;
//
//
//    /**
//     * @param ContainerInterface|null $container
//     */
//    public function setContainer(ContainerInterface $container = null)
//    {
//        $this->container = $container;
//        $this->file = $container->getParameter('kernel.project_dir') . '/public/files/country_iso.csv';
//    }

    public function load(ObjectManager $manager)
    {
        $countries = $this->parseCSV();
        if (count($countries) > 0) {
            foreach($countries as $data) {
                $country = new Country();
                $country->setCountryCode($data[1]);
                $country->setCountryName($data[0]);
                $manager->persist($country);
                $manager->flush();
            }
        }
    }

    public function parseCSV()
    {
        return [
            [0=> 'Afghanistan', 1 => 'AF'],
            [0 => 'Albania', 1 => 'AL'],
            [0 => 'Algeria', 1 => 'AlG'],
            [0 => 'Andorra', 1 => 'AN'],
            [0 => 'Angola', 1 => 'ANG'],
            [0 => 'Antigua', 1 => 'ANT'],
            [0 => 'Argentina', 1 => 'ARG'],
            [0 => 'France', 1 => 'FR'],
            [0 => 'Tunisie', 1 => 'TN'],
            [0 => 'Belgique', 1 => 'BE'],
            [0 => 'Almagne', 1  => 'DE'],
            [0 => 'Espagne', 1  => 'ES' ],
            [0 => 'Chine',  1   => 'CH']

        ];
    }

    public function getOrder()
    {
        return 1;
    }
}