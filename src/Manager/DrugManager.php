<?php

namespace App\Manager;

use App\Repository\DrugRepository;

class DrugManager extends BaseManager
{
    /**
     * @param $criteria
     * @param DrugRepository $drugRepository
     * @param $limit
     * @param $offset
     * @return \Doctrine\ORM\Tools\Pagination\Paginator|mixed
     */
    public function list($criteria, $limit, $offset, $drugRepository)
    {

        $drugs = $drugRepository->findByCriteria($criteria, $limit, $offset);

        return $drugs;
    }
}