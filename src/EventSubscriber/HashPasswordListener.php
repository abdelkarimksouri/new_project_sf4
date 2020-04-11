<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class HashPasswordListener implements EventSubscriber
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getSubscribedEvents()
   {
       return ['prePersist', 'preUpdate'];
   }

   public function prePersist(LifecycleEventArgs $args)
   {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $this->encodePassword($entity);
   }

   public function preUpdate(LifecycleEventArgs $args)
   {
       $entity = $args->getEntity();
       if (!$entity instanceof User)
       {
           return;
       }
       $this->encodePassword($entity);
       $em = $args->getEntityManager();
       $meta = $em->getClassMetadata(get_class($entity));
       $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
   }

   private function encodePassword(User $user)
   {
       if (!$user->getPassword()) {
           return;
       }

       $encoder = $this->passwordEncoder->encodePassword($user, $user->getPassword());
       $user->setPassword($encoder);
       return $user;
   }
}