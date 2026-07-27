<?php

namespace App\Repositories\Eloquent;

use App\Models\Field;
use App\Repositories\Interfaces\FieldRepositoryInterface;

class FieldRepository implements FieldRepositoryInterface
{
    public function getAllPaginated(int $perPage = 10)
    {
        return Field::with('fieldType')->latest()->paginate($perPage);
    }

    public function getById(int $id)
    {
        return Field::findOrFail($id);
    }

    public function create(array $data)
    {
        return Field::create($data);
    }

    public function update(int $id, array $data)
    {
        $field = $this->getById($id);
        $field->update($data);
        return $field;
    }

    public function delete(int $id)
    {
        $field = $this->getById($id);
        return $field->delete();
    }
}
