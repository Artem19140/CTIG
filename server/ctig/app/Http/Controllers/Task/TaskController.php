<?php

namespace App\Http\Controllers\Task;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Task\TaskResource;
use App\Http\Requests\Task\StoreTaskRequest;

class TaskController extends Controller
{

    public function index()
    {
        return TaskResource::collection(Task::all());
    }

    public function store(StoreTaskRequest $request)
    {       
        //что нет такого guid fipi
        Task::create($request->all());
        return response()->json(['result' => 'ok']);
    }

    public function show(int $id)
    {   
        $task = Task::findOrFail($id);
        $task->load(['answers']);
        return new TaskResource($task);
    }

    public function update(Request $request, Task $task)
    {
        //поменять is_actual = 0, потом просто сохранить новый вопрос
    }

    public function destroy(Task $task)
    {
        //
    }
}
