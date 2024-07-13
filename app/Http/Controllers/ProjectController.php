<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResourse;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    public function index(Request $request)
    {
        $project = QueryBuilder::for(Project::class)
            ->allowedIncludes('tasks')
            ->paginate();
        return new ProjectCollection($project);
    }



    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();
        $project = Auth::user()->projects()->create($validated);

        return new ProjectResourse($project);
    }

    public function show(Request $request, Project $project)
    {
        return (new ProjectResourse($project))
            ->load('tasks')
            ->load('members');
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();
        $project->update($validated);
        return new ProjectResourse($project);
    }

    public function destroy(Request $request, Project $project)
    {
        $project->delete();

        return response()->noContent();
    }
}
