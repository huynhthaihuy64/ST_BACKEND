<?php

namespace App\Services;

use App\Repositories\FormRepo;
use App\Services\FieldTypesFormService;

/**
 * Class FormService
 * @package App\Services
 */
class FormService
{
    /**
     * @var FormRepo
     */
    private $formRepo;

    /**
     * @var FieldTypesFormService
     */
    private $fieldTypesFormService;

    /**
     * @param FormRepo $repo
     * @param FieldTypesFormService $fieldTypesFormService
     */
    public function __construct(FormRepo $repo, FieldTypesFormService $fieldTypesFormService)
    {
        $this->formRepo = $repo;
        $this->fieldTypesFormService = $fieldTypesFormService;
    }

    /**
     * Create data fieldtypeform
     * 
     * @param int $typeform_id
     * @param string $label
     * @param int $index
     * @param string $type
     * @param int $require
     * @param string $name
     * 
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    private function getDataFieldTypesForm(int $typeform_id, string $label, int $index, string $type, int $require, string $name)
    {
        return $this->fieldTypesFormService->insert(array(
            'typeform_id' => $typeform_id,
            'label' => $label,
            'index' => $index,
            'type' => $type,
            'require' => $require,
            'name' => $name,
        ));
    }

    /**
     * Show data typeform
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return $this->formRepo->get();
    }

    /**
     * Create fieldtypeform
     * 
     * @param int $id
     */
    public function createFieldtypeForm($id)
    {
        $this->getDataFieldTypesForm($id, 'What is your name?', 1, 'text', 1, 'name');
        $this->getDataFieldTypesForm($id, 'What is your email?', 2, 'text', 1, 'email');
        $this->getDataFieldTypesForm($id, 'What is your phone number?', 3, 'text', 1, 'phone');
        $this->getDataFieldTypesForm($id, 'Submit your cv', 4, 'file', 1, 'cv');
        $this->getDataFieldTypesForm($id, 'Thanks for your submission', 5, 'submit', 1, 'submit');
    }

    /**
     * Create typeform and fieldtypeform
     * 
     * @param array $params
     * 
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function insert(array $params)
    {
        $data = $this->formRepo->insertForm($params);

        $this->createFieldtypeForm($data['id']);

        return $data;
    }

    /**
     * Show data by id
     * 
     * @param int $id
     * 
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getById(int $id)
    {
        return $this->formRepo->getById($id);
    }

    /**
     * Update data form and field type form by id
     * 
     * @param array $params
     * @param int $id
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function update(array $params, int $id)
    {
        if (isset($params['field_types_forms'])) {
            foreach ($params['field_types_forms'] as $value) {
                $this->fieldTypesFormService->update($value, $value['id']);
            }
            unset($params['field_types_forms']);
        }

        return $this->formRepo->updateForm($params, $id);
    }
}
