<?php

namespace App\Services;

use App\Repositories\FieldTypesFormRepo;

/**
 * Class FieldTypesFormService
 * @package App\Services
 */
class FieldTypesFormService
{
    /**
     * @var FieldTypesFormRepo
     */
    private $fieldTypesFormRepo;

    /**
     * @param FieldTypesFormRepo $fieldTypesFormRepo
     */
    public function __construct(FieldTypesFormRepo $fieldTypesFormRepo)
    {
        $this->fieldTypesFormRepo = $fieldTypesFormRepo;
    }

    /**
     * Create filed
     * 
     * @param array $params
     * 
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function insert(array $params)
    {
        return $this->fieldTypesFormRepo->insertField($params);
    }

    /**
     * Update field
     * 
     * @param array $params
     * @param int $id
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function update(array $params, int $id)
    {
        return $this->fieldTypesFormRepo->updateField($params, $id);
    }
}
