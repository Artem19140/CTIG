<?php

namespace Tests\Feature\Student;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function studentBody(array $overrides = []):array{
        return array_merge([
            "surname" => "Жонбеков",
            "name" => "Жонбек",
            "patronymic" => "Васыл Оглы",
            "dateBirth" => "2008-01-30",
            "surnameLatin" => "Ivanov",
            "nameLatin" => "Ivan",
            "patronymicLatin" => "Ivanovich",
            "passportNumber" => "123456",
            "passportSeries" => "AB",
            "issuedBy" => "МВД по УР",
            "issuesDate" => "2015-05-20",
            "addressReg" => "Moscow, Red Square 1",
            "migrationCardRequisite" => "MC123456789",
            "citizenship" => "Uzbekistan",
            "phone" => "+79161234567",
            "noPatronymic" => false,
        ],$overrides);
    }

    public function postStudent(array $overrides = []){
        return $this
                ->actingAs($this->user)
                ->postJson('api/students', $this->studentBody($overrides))
                ->assertHeader('content-type', 'application/json');
    }


    public function test_success_with_no_patronymic(): void{
        $response =  $this->postStudent(
            [
                "patronymic" => "",
                "patronymicLatin" => "",
                "noPatronymic" => true
            ]
        );
        $response->assertStatus(201);
        $this->assertDatabaseCount('students', 1);
    }

    public function test_fail_wrong_date(): void{
        $response =  $this->postStudent(
            [
                "dateBirth" => "2008-01-30123",
                "issuesDate" => "2015-05-20qw",
            ]
        );
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['dateBirth', 'issuesDate']);;
        $this->assertDatabaseEmpty('students');
    }

    public function test_fail_with_no_patronymic_when_must_be(): void{
        $response =  $this->postStudent(
            [
                "patronymic" => "",
                "patronymicLatin" => "",
                "noPatronymic" => false
            ]
        );
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['patronymic', 'patronymicLatin']);
        $this->assertDatabaseEmpty('students');
    }

    public function test_fail_with_patronymic_when_must_not_be(): void{
        $response =  $this->postStudent(
            [
                "patronymic" => "sdf",
                "patronymicLatin" => "sdf",
                "noPatronymic" => true
            ]
        );
        $response->assertStatus(422);
        $this->assertDatabaseEmpty('students');
    }

    public function test_fail_no_required_params(): void{
        $response =  $this->postStudent(
            [
                "surname" => "",
                "name" => "",
                "patronymic" => "",
                "dateBirth" => "",
                "surnameLatin" => "",
                "nameLatin" => "",
                "patronymicLatin" => "",
                "passportNumber" => "",
                "passportSeries" => "",
                "issuedBy" => "",
                "issuesDate" => "",
                "addressReg" => "",
                "migrationCardRequisite" => "",
                "citizenship" => "",
                "phone" => "",
                "noPatronymic" => "",
            ]
        );
        $response->assertStatus(422)
         ->assertJsonValidationErrors([
                 'surname',
                 'name',
                 'dateBirth',
                 'surnameLatin',
                 'nameLatin',
                 'passportNumber',
                 'passportSeries',
                 'issuedBy',
                 'issuesDate',
                 'addressReg',
                 'migrationCardRequisite',
                 'citizenship',
                 'phone',
                 'noPatronymic',
             ]);
         $this->assertDatabaseEmpty('students');
    }

    public function test_fail_int_instead_string(): void{
        $response =  $this->postStudent(
            [
                "surname" => 123,
                "name" => 123,
                "patronymic" => 123,
                "dateBirth" => 123,
                "surnameLatin" => 123,
                "nameLatin" => 13,
                "patronymicLatin" => 123,
                "passportNumber" => 123,
                "passportSeries" => 123,
                "issuedBy" => 123,
                "issuesDate" => 123,
                "addressReg" => 234,
                "migrationCardRequisite" => 234,
                "citizenship" => 234,
                "phone" => 234,
                "noPatronymic" => 2344,
            ]
        );
        $response->assertStatus(422)->assertJsonValidationErrors([
                 'surname',
                 'name',
                 "patronymic",
                 'dateBirth',
                 'surnameLatin',
                 'nameLatin',
                  "patronymicLatin",
                 'passportNumber',
                 'passportSeries',
                 'issuedBy',
                 'issuesDate',
                 'addressReg',
                 'migrationCardRequisite',
                 'citizenship',
                 'phone',
                 'noPatronymic',
             ]);;
        $this->assertDatabaseEmpty('students');
    }

     public function test_fail_array_instead_string(): void{
        $response =  $this->postStudent(
            [
                "surname" => [],
                "name" =>  [],
                "patronymic" => [],
                "dateBirth" =>  [],
                "surnameLatin" =>  [],
                "nameLatin" =>  [],
                "patronymicLatin" =>  [],
                "passportNumber" => [],
                "passportSeries" =>  [],
                "issuedBy" =>  [],
                "issuesDate" =>  [],
                "addressReg" =>  [],
                "migrationCardRequisite" => [],
                "citizenship" =>  [],
                "phone" =>  [],
                "noPatronymic" =>  [],
            ]
        );
        $response->assertStatus(422)->assertJsonValidationErrors([
                 'surname',
                 'name',
                 "patronymic",
                 'dateBirth',
                 'surnameLatin',
                 'nameLatin',
                  "patronymicLatin",
                 'passportNumber',
                 'passportSeries',
                 'issuedBy',
                 'issuesDate',
                 'addressReg',
                 'migrationCardRequisite',
                 'citizenship',
                 'phone',
                 'noPatronymic',
             ]);;
        $this->assertDatabaseEmpty('students');
    }
}
