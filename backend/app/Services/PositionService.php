<?php

namespace App\Services;

use App\Repositories\PositionRepo;

/**
 * Class PositionService
 * @package App\Services
 */
class PositionService
{
    /**
     * @var PositionRepo
     */
    private $positionRepo;

    /**
     * @param PositionRepo $positionRepo
     */
    public function __construct(PositionRepo $positionRepo)
    {
        $this->positionRepo = $positionRepo;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll()
    {
        return $this->positionRepo->findAll();
    }
}
