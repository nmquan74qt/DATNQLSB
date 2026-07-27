<?php

namespace App\Services;

use App\Repositories\Interfaces\FieldRepositoryInterface;
use Illuminate\Support\Str;

class FieldService
{
    protected $fieldRepository;

    public function __construct(FieldRepositoryInterface $fieldRepository)
    {
        $this->fieldRepository = $fieldRepository;
    }

    public function getAllFieldsPaginated(int $perPage = 10)
    {
        return $this->fieldRepository->getAllPaginated($perPage);
    }

    public function createField(array $data)
    {
        // Business logic: Generate slug
        $data['slug'] = Str::slug($data['name']) . '-' . time();
        $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : false;

        return $this->fieldRepository->create($data);
    }

    public function getFieldById(int $id)
    {
        return $this->fieldRepository->getById($id);
    }

    public function updateField(int $id, array $data)
    {
        $field = $this->fieldRepository->getById($id);

        if ($data['name'] !== $field->name) {
            $data['slug'] = Str::slug($data['name']) . '-' . time();
        }
        
        $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : false;

        return $this->fieldRepository->update($id, $data);
    }

    public function deleteField(int $id)
    {
        return $this->fieldRepository->delete($id);
    }
}
