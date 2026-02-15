<?php

namespace App\Http\Controllers\Subblock;

use App\Exceptions\EntityNotFoundExсeption;
use App\Http\Requests\Subblock\SubblockRequest;
use App\Http\Resources\Subblock\SubblockResource;
use App\Models\ExamBlock;
use App\Models\Subblock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubblockController extends Controller
{
    public function index()
    {
        //
    }

    public function store(SubblockRequest $request)
    {
        $examBlock = ExamBlock::find($request->validated('examBlockId'));
        if(!$examBlock){
            throw new EntityNotFoundExсeption('блок экзамена');
        }
        $subblock = Subblock::create([
            'name' => $request->input('name'),
            'order' => $request->input('order'),
            'exam_block_id' => $request->input('examBlockId'),
            'min_mark' => $request->input('minMark'),
            'creator_id' => $request->user()->id,
        ]);
        return $this->created(new SubblockResource($subblock));
    }

    public function show(Subblock $subblock)
    {
        //
    }

    public function update(Request $request, Subblock $subblock)
    {
        //
    }

    public function destroy(Subblock $subblock)
    {
        //
    }
}
