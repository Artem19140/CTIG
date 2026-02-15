<?php

namespace App\Http\Controllers\TaskVariant;

use App\Exceptions\BusinessException;
use App\Exceptions\EntityNotFoundExсeption;
use App\Http\Requests\TaskVariant\TaskVariantStoreRequest;
use App\Http\Resources\TaskVariant\TaskVariantResource;
use App\Models\Task;
use App\Models\TaskVariant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskVariantController extends Controller
{
    public function index()
    {
        //Можно плоско получить здесь по taskId все
    }

    public function store(TaskVariantStoreRequest $request)
    {
        $task = Task::find($request->validated('taskId'));
        if(!$task){
            throw new EntityNotFoundExсeption('Задания');
        }
        $isFipiGuidExist = TaskVariant::where('fipi_guid', $request->validated('fipiGuid'))->exists();
        if($isFipiGuidExist){
            throw new BusinessException('Вопрос с таким идентификатором фипи уже сущестует');
        }
        $taskVariant = TaskVariant::create([
            'fipi_guid' => $request->validated('fipiGuid'),
            'contain' => $request->validated('contain'),
            'task_id' => $request->validated('taskId'),
            'group_id' => $request->validated('groupId'),
            'mark' => $request->validated('mark')
        ]);
        return $this->created(new TaskVariantResource($taskVariant));
    }

    public function show(TaskVariant $taskVariant)
    {
        $taskVariant->load('answers');
        return new TaskVariantResource($taskVariant);
    }

    public function update(Request $request, TaskVariant $taskVariant)
    {
        //
    }

    public function destroy(TaskVariant $taskVariant)
    {
        //
    }
}
