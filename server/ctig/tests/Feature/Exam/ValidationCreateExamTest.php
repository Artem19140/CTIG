<?php

namespace Tests\Feature\Exam;

use App\Models\Address;
use App\Models\ExamType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValidationCreateExamTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected ExamType $examType;
    protected Address $address;
    protected $beginTime;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginTime = Carbon::now()->addDay()->format('Y-m-d H:i:s');
        $this->user = User::factory()->create();
        $this->examType = ExamType::factory()->create();
        $this->address = Address::factory()->create();
    }

    public function examBody(array $overrides = []):array{
        return array_merge([
            'beginTime'   => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
            'examTypeId' => $this->examType->id,
            'addressId'   => $this->address->id,
            'capacity'     => 10,
            'testers'      => [$this->user->id],
            'comment'      => 'Да, я добавил экзамен!'
        ],$overrides);
    }

    public function postExam(array $overrides = []){
        return $this
                ->actingAs($this->user)
                ->postJson('api/exams', $this->examBody($overrides))
                ->assertHeader('content-type', 'application/json');
    }

    public function test_fail_no_required_params(): void{
        $response =  $this->postExam([
                                        'beginTime'   => '',
                                        'examTypeId' => '',
                                        'addressId'   => '',
                                        'capacity'     => '',
                                        'testers'      => '',
                                        'comment'      => '',
                                    ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['beginTime', 'examTypeId', 'addressId', 'capacity', 'testers']);
    }

    public function test_fail_wrong_date(): void{
        $response =  $this->postExam([
            'beginTime'   => $this->beginTime."234",
            'examTypeId' => 4,
            'addressId'   => 3,
            'capacity'     => 2,
            'testers'      => [1],
            'comment'      => '',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['beginTime']);
    }

    public function test_fail_string_instead_int(): void{
        $response =  $this->postExam([
            'beginTime'   => $this->beginTime,
            'examTypeId' => "sdf",
            'addressId'   => "wer",
            'capacity'     => "sdf",
            'testers'      => ["asdf"],
            'comment'      => '',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['examTypeId', 'addressId', 'capacity', 'testers.0']);
    }

    public function test_fail_int_instead_string(): void{
        $response =  $this->postExam([
            'beginTime'   => 1243,
            'examTypeId' => 123,
            'addressId'   => 123,
            'capacity'     => 1,
            'testers'      => [123],
            'comment'      => 123,
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['beginTime','comment' ]);
    }

    public function test_fail_int_less_zero(): void{
        $response =  $this->postExam([
            'beginTime'   => $this->beginTime,
            'examTypeId' => -123,
            'addressId'   => -45,
            'capacity'     => -123,
            'testers'      => [-1],
            'comment'      =>'',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['examTypeId', 'addressId', 'capacity', 'testers.0']);
    }

    public function test_fail_arr_instead_other_types(): void{
        $response =  $this->postExam([
            'beginTime'   => [],
            'examTypeId' => [],
            'addressId'   => [],
            'capacity'     => [],
            'testers'      => [1],
            'comment'      =>[],
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['beginTime', 'examTypeId', 'addressId', 'capacity', 'comment']);
    }

}
