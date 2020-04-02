<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface,ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 10; $i++) {

            $encoder = $this->container->get('security.password_encoder');
            $user = new User();
            $user->setEmail("abdel".$i."@gmail.com");
            $plainPassword = 'myfarma';
            $encoded = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            $user->setRoles(['ROLE_USER']);
            $this->addReference('user_'.$i, $user);

            $manager->persist($user);

        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}