<?php

namespace App\Services;

use App\Models\Block;

class BlockService
{
    public function getAll()
    {
        return Block::with('project')->latest()->get();
    }

    public function create(array $data)
    {
        return Block::create(['project_id' => $data['project_id'],'block' => $data['block'],]);
    }

    public function find($id)
    {
        return Block::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $block = Block::findOrFail($id);
        $block->update(['project_id' => $data['project_id'],'block' => $data['block']]);
        return $block;
    }

    public function delete($id)
    {
        return Block::findOrFail($id)->delete();
    }
}
