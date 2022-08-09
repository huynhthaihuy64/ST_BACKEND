<?php

namespace App\Services;

use App\Repositories\TechnologyRepo;

/**
 * Class TechnologyService
 * @package App\Services
 */
class TechnologyService
{
    /**
     * @var TechnologyRepo
     */
    private $technologyRepo;

    /**
     * @param TechnologyRepo $technologyRepo
     */
    public function __construct(TechnologyRepo $technologyRepo)
    {
        $this->technologyRepo = $technologyRepo;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll()
    {
        return $this->technologyRepo->findAll();
    }
}
