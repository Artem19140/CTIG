<?php

namespace Tests\Feature\ExamBlock;

use App\Models\ExamType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExamBlockTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected ExamType $examType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->examType = ExamType::factory()->create();
    }
    public function examBody(array $overrides = []):array{
        return array_merge([
            'name' =>'Название экзамена',
            'minMark' =>3,
            'examTypeId' =>$this->examType->id,
            'order' =>3
        ],$overrides);
    }

    public function postExamBlock(array $overrides = []){
        return $this
                ->actingAs($this->user)
                ->postJson('api/exam-blocks', $this->examBody($overrides))
                ->assertHeader('content-type', 'application/json');
    }

    public function test_success_create(){
        $this->postExamBlock()
            ->assertCreated()
            ->assertJsonPath('data.minMark', 3)
            ->assertJsonPath('data.name', 'Название экзамена');
        $this->assertDatabaseCount('exam_blocks', 1);
        $this->assertDatabaseHas('exam_blocks', [
            'name' => 'Название экзамена',
            'min_mark' => 3, 
            'exam_type_id' => $this->examType->id
        ]);
    }


    public function test_fail_not_exists_exam_type(){
        $this->postExamBlock([
            'examTypeId' => $this->examType->id + 1
        ])
            ->assertUnprocessable();
        $this->assertDatabaseEmpty('exam_blocks');
    }

    public function test_fail_validation_integer_less_zero(){
        $this->postExamBlock([
            'minMark' => -1,
            'order' => -1, 
            'examTypeId'=>-1
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['minMark', 'order', 'examTypeId']);
        $this->assertDatabaseEmpty('exam_blocks');
    }

    public function test_fail_validation_integer_equeal_zero(){
        $this->postExamBlock([
            'minMark' => 0,
            'order' => 0, 
            'examTypeId'=>0
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['minMark', 'order', 'examTypeId']);
        $this->assertDatabaseEmpty('exam_blocks');
    }

    public function test_fail_validation_wrong_types(){
        $this->postExamBlock([
            'minMark' => "sd",
            'order' =>"sd", 
            'examTypeId'=>"sd",
            'name'=>1243
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['minMark', 'order', 'examTypeId','name']);
        $this->assertDatabaseEmpty('exam_blocks');
    }

    public function test_fail_validation_empty_requires_fields(){
        $this->postExamBlock([
            'minMark' => "",
            'order' => "", 
            'examTypeId'=> "",
            'name'=> ""
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['minMark', 'order', 'examTypeId','name']);
        $this->assertDatabaseEmpty('exam_blocks');
    }
    
}
