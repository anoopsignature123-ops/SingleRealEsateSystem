<?php

namespace App\Services;

use App\Models\PlcRate;
use App\Models\PlotDetail;

class PlcRateService
{
    public function getAll()
    {
        return PlcRate::with(['plotType'])->latest()->get();
    }

    public function create(array $data)
    {
        PlotDetail::where('plot_type_id', $data['plot_type_id'])->update(['plc_rate' => $data['rate']]);

        return PlcRate::create($data);
    }

    public function find(int $id)
    {
        return PlcRate::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $plcRate = $this->find($id);
        PlotDetail::where('plot_type_id', $data['plot_type_id'])->update(['plc_rate' => $data['rate']]);
        $plcRate->update($data);

        return $plcRate;
    }

    public function delete(int $id)
    {
        $plcRate = $this->find($id);

        return $plcRate->delete();
    }
}
