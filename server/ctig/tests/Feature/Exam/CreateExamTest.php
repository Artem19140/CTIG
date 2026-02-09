<?php

namespace Tests\Feature\Exam;

use App\Models\ExamType;
use App\Models\User;
use App\Models\Address;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateExamTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected ExamType $examType;
    protected Address $address;

    protected function setUp(): void
    {
        parent::setUp();

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

    public function test_success_registration(): void{        
        $response =  $this->postExam();
        $response->assertStatus(201);
        $this->assertDatabaseCount('exams', 1);
    }

     public function test_success_no_conflict(): void{
        $response =  $this->postExam(['addressId'   => $this->address->id]);
        $response->assertStatus(201);
        
        $response =  $this->postExam(
            [
                            'addressId'   => Address::factory()->create()->id,
                            'testers' => [User::factory()->create()->id]
                        ]);
        $response->assertStatus(201);
        $this->assertDatabaseCount('exams', 2);
    }

    public function test_fail_unauthorized (): void{        
        $this
            ->postJson('api/exams', $this->examBody())
            ->assertJson([
                'message' => 'Unauthenticated.',
            ])
            ->assertStatus(401);
    }

    public function test_registration_fail_exam_type_not_exists(): void{
        
        $response =  $this->postExam(['examTypeId' => $this->examType->id + 1]);
        $response->assertStatus(422);
        $this->assertDatabaseEmpty('exams');
    }

    public function test_registration_fail_address_not_exists(): void{        
        $response =  $this->postExam(['addressId'   => $this->address->id + 1]);
        $response->assertStatus(422);
        $this->assertDatabaseEmpty('exams');
    }
    
    public function test_fail_testers_not_exists(): void{        
        $response =  $this->postExam(['testers'   => [$this->user->id+10]]);
        $response->assertStatus(422);
        $this->assertDatabaseEmpty('exams');
    }

    public function test_fail_conflict_exams_before_begin_time(): void{  
        $response =  $this->postExam(['beginTime'   => Carbon::now()->addDay()->format('Y-m-d H:i:s')]);
        $response->assertStatus(201);
        
        $response =  $this->postExam(['beginTime'   => Carbon::now()->addDay()->subMinutes(10)->format('Y-m-d H:i:s')]);
        $response->assertStatus(422);
        $this->assertDatabaseCount('exams', 1);
    }

    public function test_fail_conflict_exams_during(): void{
        $response =  $this->postExam(['beginTime'   => Carbon::now()->addDay()->format('Y-m-d H:i:s')]);
        $response->assertStatus(201);
        
        $response =  $this->postExam(['beginTime'   => Carbon::now()->addDay()->addMinutes(20)->format('Y-m-d H:i:s')]);
        $response->assertStatus(422);
        $this->assertDatabaseCount('exams', 1);
    }

    public function test_fail_conflict_testers(): void{
        $response =  $this->postExam(['addressId'   => $this->address->id]);
        $response->assertStatus(201);
        
        $response =  $this->postExam(['addressId'   => Address::factory()->create()->id]);
        $response->assertStatus(422);
        $this->assertDatabaseCount('exams', 1);
    }
}
