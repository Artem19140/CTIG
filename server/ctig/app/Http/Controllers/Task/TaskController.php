<?php

namespace App\Http\Controllers\Task;

use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Subblock;
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
        if(!TaskType::tryFrom(request()->input('type'))){
            throw new BusinessException('Неверный тип задания');
        }
        $subblock = Subblock::find(request()->input('subblockId'));
        if(!$subblock){
            throw new EntityNotFoundExсeption('подблок');
        }
        $task = Task::create([
            'creator_id' => request()->user()->id, 
            'subblock_id' =>  request()->input('subblockId'),
            'type' => request()->input('type'),
            'order' => request()->input('order')
        ]);
        return $this->created(new TaskResource($task));
    }

    public function show(Task $task)
    {   
        $task->load(['variants.answers']);
        return new TaskResource($task);
    }

    public function update(Request $request, Task $task)
    {
        //поменять is_actual = 0, потом просто сохранить новый вопрос
    }

    public function destroy(Task $task)
    {
        $task->is_active= false;
        $task->save();
        return $this->noContent();
    }
}
