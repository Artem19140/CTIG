<?php

namespace Tests\Feature\Answer;

use App\Models\TaskVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected TaskVariant $taskVariant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->taskVariant = TaskVariant::factory()->create();
    }
    public function answerBody(array $overrides = []):array{
        return array_merge([
            'contain' => 'Ответ 1',
            'isCorrect' =>false,
            'taskVariantId' =>$this->taskVariant->id,
            'mark' => 1
        ],$overrides);
    }

    public function postAnswer(array $overrides = []){
        return $this
                ->actingAs($this->user)
                ->postJson('api/answers', $this->answerBody($overrides))
                ->assertHeader('content-type', 'application/json');
    }

    public function test_success_create(){
        $this->postAnswer()
            ->assertCreated()
            ->assertJsonPath('data.mark', 1)
            ->assertJsonPath('data.isCorrect', false)
            ->assertJsonPath('data.contain', 'Ответ 1');
        $this->assertDatabaseCount('answers', 1);
        $this->assertDatabaseHas('answers', [
            'contain' => 'Ответ 1',
            'mark' => 1, 
            'is_correct' => false,
            'task_variant_id' => $this->taskVariant->id
        ]);
    }

    public function test_fail_not_exists_task_variant(){
        $this->postAnswer([
            'taskVariantId' => $this->taskVariant->id + 1
        ])
            ->assertUnprocessable();
        $this->assertDatabaseEmpty('answers');

    }




    /////////

    public function test_fail_validation_integer_less_zero(){
        $this->postAnswer([
            'mark' => -1,
            'taskVariantId' => -1, 
            'isCorrect'=>false,
            'contain' => "wer"
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['mark', 'taskVariantId']);
        $this->assertDatabaseEmpty('answers');
    }

    public function test_fail_validation_integer_equeal_zero(){
        $this->postAnswer([
            'mark' => 0,
            'taskVariantId' => 0, 
            'isCorrect'=>true,
            'contain' => "wer"
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['mark', 'taskVariantId']);
        $this->assertDatabaseEmpty('answers');
    }

    public function test_fail_validation_wrong_types(){
        $this->postAnswer([
            'mark' => "sd",
            'taskVariantId' =>"sd", 
            'isCorrect'=>"sd",
            'contain' => 1
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['mark', 'taskVariantId', 'isCorrect','contain']);
        $this->assertDatabaseEmpty('answers');
    }

    public function test_fail_validation_empty_requires_fields(){
        $this->postAnswer([
            'mark' => "",
            'taskVariantId' => "", 
            'isCorrect'=> "",
            'contain' => ""
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['mark', 'taskVariantId', 'isCorrect','contain']);
        $this->assertDatabaseEmpty('answers');
    }
}
