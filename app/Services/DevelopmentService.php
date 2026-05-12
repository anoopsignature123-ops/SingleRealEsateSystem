<?php

namespace App\Services;

use App\Models\Development;

class DevelopmentService
{
    public function getAll()
    {
        return Development::latest()->get();
    }

    public function create(array $data)
    {
        return Development::create($data);
    }

    public function find(int $id)
    {
        return Development::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $development = $this->find($id);

        $development->update($data);

        return $development;
    }

    public function delete(int $id)
    {
        $development = $this->find($id);

        return $development->delete();
    }
}
