<?php

namespace App\Services;

use App\Models\PlotType;

class PlotTypeService
{
    public function getAll()
    {
        return PlotType::latest()->get();
    }

    public function create(array $data)
    {
        return PlotType::create($data);
    }

    public function find($id)
    {
        return PlotType::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $plotType = PlotType::findOrFail($id);

        $plotType->update($data);

        return $plotType;
    }

    public function delete($id)
    {
        return PlotType::findOrFail($id)->delete();
    }
}
