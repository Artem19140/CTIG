<?php

namespace Tests\Feature\Exam;

use App\Models\Address;
use App\Models\ExamType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExamValidationTest extends TestCase
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
    public function test_exam_no_required_params(): void{
        $data = [
            'beginTime'   => '',
            'examTypeId' => '',
            'addressId'   => '',
            'capacity'     => '',
            'testers'      => '',
            'comment'      => '',
        ];
        
        
        $response = $this
            ->actingAs($this->user)
            ->postJson('api/exams', $data);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['beginTime', 'examTypeId', 'addressId', 'capacity', 'testers'])
            ->assertJsonStructure();
    }

    public function test_exam_no_wrong_date(): void{
        $data = [
            'beginTime'   => '2026-07-171 19:02:00',
            'examTypeId' => $this->examType->id,
            'addressId'   => $this->address->id,
            'capacity'     => 10,
            'testers'      => [200],
            'comment'      => 'Да, я добавил экзамен!',
        ];
        
        
        $response = $this
            ->actingAs($this->user)
            ->postJson('api/exams', $data);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['beginTime'])
            ->assertValid(['examTypeId', 'addressId', 'capacity', 'testers'])
            ->assertJsonStructure();
    }

}
