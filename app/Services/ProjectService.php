<?php

namespace App\Services;

use App\Models\Project;

class ProjectService
{
    public function getAll()
    {
        return Project::latest()->get();
    }

    public function create(array $data)
    {
        return Project::create($data);
    }

    public function find($id)
    {
        return Project::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $project = Project::findOrFail($id);

        $project->update($data);

        return $project;
    }

    public function delete($id)
    {
        return Project::findOrFail($id)->delete();
    }
}
