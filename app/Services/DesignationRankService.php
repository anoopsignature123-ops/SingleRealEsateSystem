<?php

namespace App\Services;

use App\Models\DesignationRank;

class DesignationRankService
{
    public function getAll()
    {
        return DesignationRank::get();
    }

    public function create(array $data)
    {
        return DesignationRank::create($data);
    }

    public function find(int $id)
    {
        return DesignationRank::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $designationRank = $this->find($id);

        $designationRank->update($data);

        return $designationRank;
    }

    public function delete(int $id)
    {
        $designationRank = $this->find($id);

        return $designationRank->delete();
    }
}
