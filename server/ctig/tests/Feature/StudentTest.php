<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;
    protected function validUserData(): array
{
    return [
        'surname' => 'Жонбеков',
        'name' => 'Жонбек',
        'patronymic' => 'Васыл Оглы',
        'dateBirth' => '1990-01-15',
        'surnameLatin' => 'Ivanov',
        'nameLatin' => 'Ivan',
        'patronymicLatin' => 'Ivanovich',
        'passportNumber' => '123456',
        'passportSeries' => 'AB',
        'issuedBy' => 'МВД по УР',
        'issuesDate' => '2015-05-20',
        'addressReg' => 'Moscow, Red Square 1',
        'migrationCardRequisite' => 'MC123456789',
        'citizenship' => 'Uzbekistan',
        'phone' => '+79161234567',
        'no_patronymic' => false,
    ];
}

    // public function test_no_patromymic(){
    //     $data = $this->validUserData();
    //     $data['patronymic'] = '';
    //     foreach ($data as $key => $value) {
    //         $data[$key] = '';
    //     }
    //     $response = $this->postJson('/api/students',
    //         $data
    //     );

    //     $response->assertStatus(422)
    //         ->assertJsonValidationErrors('patronymic')
    //         ->assertValid('name');
        
    //     $response->assertJsonStructure();
        
    // }
}
