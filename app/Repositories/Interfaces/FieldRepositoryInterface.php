<?php

namespace App\Repositories\Interfaces;

interface FieldRepositoryInterface
{
    /**
     * Get all fields with pagination.
     */
    public function getAllPaginated(int $perPage = 10);

    /**
     * Get field by ID.
     */
    public function getById(int $id);

    /**
     * Create a new field.
     */
    public function create(array $data);

    /**
     * Update an existing field.
     */
    public function update(int $id, array $data);

    /**
     * Delete a field.
     */
    public function delete(int $id);
}
