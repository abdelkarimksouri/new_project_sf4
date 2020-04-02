<?php

namespace App\Manager;

class PharmacyManager extends BaseManager
{

    /**
     * @param $criteria
     * @param $limit
     * @param $offset
     * @param $pharmacyRepository
     * @return mixed
     */
    public function list($criteria, $limit, $offset, $pharmacyRepository)
    {
        $pharmacies = $pharmacyRepository->findBySomeField($criteria, $limit, $offset);

        return $pharmacies;
    }
}