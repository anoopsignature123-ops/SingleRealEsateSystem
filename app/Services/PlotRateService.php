<?php

namespace App\Services;

use App\Models\PlotRate;
use Illuminate\Http\Request;

class PlotRateService
{
    public function getAll(?Request $request = null)
    {
        $query = PlotRate::with(['project', 'block']);
        if ($request?->project_id) {
            $query->where('project_id', $request->project_id);
        }
        if ($request?->block_id) {
            $query->where('block_id', $request->block_id);
        }

        return $query->latest()->get();
    }

    public function create(array $data)
    {
        return PlotRate::create($data);
    }

    public function find(int $id)
    {
        return PlotRate::with(['project', 'block'])->findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $plotRate = $this->find($id);
        $plotRate->update($data);

        return $plotRate;
    }

    public function delete(int $id)
    {
        $plotRate = $this->find($id);

        return $plotRate->delete();
    }
}
