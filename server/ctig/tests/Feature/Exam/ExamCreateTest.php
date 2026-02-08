<?php

namespace Tests\Feature\Exam;

use App\Models\ExamType;
use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExamCreateTest extends TestCase
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

    public function test_exam_success_registration(): void{
        $data = [
            'beginTime'   => '2026-07-17 19:02:00',
            'examTypeId' => $this->examType->id,
            'addressId'   => $this->address->id,
            'capacity'     => 10,
            'testers'      => [$this->user->id],
            'comment'      => 'Да, я добавил экзамен!'
        ];
        
        
        $response = $this
            ->actingAs($this->user)
            ->postJson('api/exams', $data);
        $response->assertStatus(200); //201 created

        $this->assertDatabaseHas('exams',[
            'begin_time'   => '2026-07-17 19:02:00',
            'exam_type_id' => $this->examType->id,
            'address_id'   => $this->address->id,
            'capacity'     => 10,
            'comment'      => 'Да, я добавил экзамен!',
        ]);
    }

    public function test_exam_registration_wrong_exam_type_id(): void{
        $data = [
            'beginTime'   => '2026-07-17 19:02:00',
            'examTypeId' => $this->examType->id +1,
            'addressId'   => $this->address->id,
            'capacity'     => 10,
            'testers'      => [$this->user->id],
            'comment'      => 'Да, я добавил экзамен!',
        ];
        
        
        $response = $this
            ->actingAs($this->user)
            ->postJson('api/exams', $data);
        $response->assertStatus(422);
    }

    public function test_exam_registration_wrong_address_id(): void{
        $data = [
            'beginTime'   => '2026-07-17 19:02:00',
            'examTypeId' => $this->examType->id,
            'addressId'   => $this->address->id+1,
            'capacity'     => 10,
            'testers'      => [$this->user->id],
            'comment'      => 'Да, я добавил экзамен!',
        ];
        
        
        $response = $this
            ->actingAs($this->user)
            ->postJson('api/exams', $data);
        $response->assertStatus(422);
    }
    
    public function test_exam_wrong_testers(): void{
        $data = [
            'beginTime'   => '2026-07-17 19:02:00',
            'examTypeId' => $this->examType->id,
            'addressId'   => $this->address->id,
            'capacity'     => 10,
            'testers'      => [200],
            'comment'      => 'Да, я добавил экзамен!',
        ];
        
        
        $response = $this
            ->actingAs($this->user)
            ->postJson('api/exams', $data);
        $response->assertStatus(422); 
    }

    public function test_exam_conflict_before_begin(): void{
        $data = [
            'beginTime'   => '2026-07-17 19:02:00',
            'examTypeId' => $this->examType->id,
            'addressId'   => $this->address->id,
            'capacity'     => 10,
            'testers'      => [$this->user->id],
            'comment'      => 'Да, я добавил экзамен!',
        ];
        
        
        $response = $this
            ->actingAs($this->user)
            ->postJson('api/exams', $data);
        $response->assertStatus(200); //201

        $dataNew = [
            'beginTime'   => '2026-07-17 18:50:00',
            'examTypeId' => $this->examType->id,
            'addressId'   => $this->address->id,
            'capacity'     => 10,
            'testers'      => [$this->user->id],
            'comment'      => 'Да, я добавил экзамен!',
        ];

        $response = $this
            ->actingAs($this->user)
            ->postJson('api/exams', $dataNew);
        $response->assertStatus(422); //201
    }

    public function test_exam_conflict_during(): void{
        $data = [
            'beginTime'   => '2026-07-17 19:02:00',
            'examTypeId' => $this->examType->id,
            'addressId'   => $this->address->id,
            'capacity'     => 10,
            'testers'      => [$this->user->id],
            'comment'      => 'Да, я добавил экзамен!',
        ];
        
        
        $response = $this
            ->actingAs($this->user)
            ->postJson('api/exams', $data);
        $response->assertStatus(200); //201

        $dataNew = [
            'beginTime'   => '2026-07-17 19:50:00',
            'examTypeId' => $this->examType->id,
            'addressId'   => $this->address->id,
            'capacity'     => 10,
            'testers'      => [$this->user->id],
            'comment'      => 'Да, я добавил экзамен!',
        ];

        $response = $this
            ->actingAs($this->user)
            ->postJson('api/exams', $dataNew);
        $response->assertStatus(422); //201
    }

    public function test_exam_no_conflict(): void{
        $data = [
            'beginTime'   => '2026-07-17 19:02:00',
            'examTypeId' => $this->examType->id,
            'addressId'   => $this->address->id,
            'capacity'     => 10,
            'testers'      => [$this->user->id],
            'comment'      => 'Да, я добавил экзамен!',
        ];
        
        
        $response = $this
            ->actingAs($this->user)
            ->postJson('api/exams', $data);
        $response->assertStatus(200); //201

        $dataNew = [
            'beginTime'   => '2026-07-17 18:50:00',
            'examTypeId' => $this->examType->id,
            'addressId'   => Address::factory()->create()->id,
            'capacity'     => 10,
            'testers'      => [User::factory()->create()->id],
            'comment'      => 'Да, я добавил экзамен!',
        ];

        $response = $this
            ->actingAs($this->user)
            ->postJson('api/exams', $dataNew);
        $response->assertStatus(200); //201
    }
}
