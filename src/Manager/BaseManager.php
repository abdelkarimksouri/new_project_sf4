<?php

namespace App\Manager;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;


class BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @param $entity
     * @param bool $doFlush
     * @return mixed
     */
    public function save($entity, $doFlush = true)
    {
        $this->entityManager->persist($entity);
        if ($doFlush) {
            $this->entityManager->flush();
        }

        return $entity;
    }

    /**
     * Delete the given entity.
     *
     * @param object $entity An entity instance
     */
    public function remove($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }


    /**
     * @return ObjectRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Flush persisted entities.
     */
    public function flush()
    {
        $this->entityManager->flush();
    }

    /**
     * Refresh persisted entities.
     *
     * @param object $entity
     */
    public function refresh($entity)
    {
        $this->entityManager->refresh($entity);
    }

    /**
     * Clears the repository, causing all managed entities to become detached.
     */
    public function clear()
    {
        $this->entityManager->clear();
    }

}