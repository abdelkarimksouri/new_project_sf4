<?php

namespace App\DataFixtures;

use App\Entity\Media;
use App\Entity\Advert;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AdvertFixtures  extends Fixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var string
     */
    private $file;


    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->file = $container->getParameter('kernel.project_dir') . '/public/files/country_iso.csv';
    }

    public function load(ObjectManager $manager)
    {
        $listAdverts = [
            [
                'title'   => 'Recherche développpeur Symfony',
                'id'      => 1,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
                'date'    => new \Datetime(),
                'slug'    => 'offre emploit',
            ],
            [
                'title'   => 'Mission de webmaster',
                'id'      => 2,
                'author'  => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date'    => new \Datetime(),
                'slug'    => 'vente',
            ],
            [
                'title'   => 'Offre de stage webdesigner',
                'id'      => 3,
                'author'  => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                'date'    => new \Datetime(),
                'slug'    => 'publicité',
            ]
        ];
        $array = [
            'title'   => 'Recherche développpeur Symfony',
            'id'      => 1,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
            'date'    => new \Datetime(),
            'slug'    => 'offre emploit',
        ];

        for ($i = 1; $i < 10; $i++) {
            $advert = new Advert();
            $advert->setTitle($array['title'].$i);
            $advert->setAuthor($array['author'].$i);
            $advert->setContent($array['content']);
            $advert->setDate($array['date']);
            $advert->setPublished(true);
            $advert->setNbApplications(1);
            $advert->setSlug($array['slug'].$i);
            $advert->setImage($this->getReference('media_' . $i));
            $advert->setUpdatedAt($array['date']);
            $manager->persist($advert);

            $manager->flush();
        }
    }


    public function getOrder()
    {
        return 8;
    }
}