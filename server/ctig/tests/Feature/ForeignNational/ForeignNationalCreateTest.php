<?php

namespace Tests\Feature\ForeignNational;

use App\Enums\CounterKey;
use App\Enums\UserRoles;
use App\Models\Address;
use App\Models\Counter;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Center;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ForeignNationalCreateTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected ExamType $examType;
    protected Address $address;
    protected Exam $exam;

    protected function setUp():void{
            parent::setUp();
            Center::factory()->create();
            
            
            $operatorRole = Role::create([
                'name' => UserRoles::Operator,
            ]);

            $this->user = User::factory()->create();
            $this->user->roles()->attach($operatorRole);

            $this->examType = ExamType::factory()->create();
            $this->address = Address::factory()->create();
            
            Carbon::setTestNow(now());

            $this->exam = Exam::create([
                'begin_time' => Carbon::now($this->user->center->time_zone)->addDay(),
                'end_time' => Carbon::now($this->user->center->time_zone)->addDay()->addMinutes(ExamType::inRandomOrder()->first()->duration),
                'begin_time_utc' => Carbon::now()->addDay(),
                'exam_type_id' => ExamType::inRandomOrder()->first()->id,
                'creator_id' => $this->user->id,
                'capacity'=>$this->address->max_capacity,
                'address_id' => $this->address->id,
                'center_id' => Center::inRandomOrder()->first()->id
            ]);
            Storage::fake('private');
            Counter::create([
                'key' => CounterKey::RegNumKey,
                'value' => Carbon::now()->format('y').'0000',
                'center_id' => 1
            ]);
        }
    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); // сброс фиксации
    }
    protected function foreignNationalBody(array $overrides = [])
    {
        return array_merge([
            'addressReg' => 'Ижевск, Пушкинская 131',
            'citizenship' => 'UZ',
            'comment' => 'ываыва',
            'dateBirth' => '2005-10-10',
            'examId' => $this->exam->id,
            'gender' => 'M',
            'hasPayment' => false,
            'issuedBy' => 'МВД по УР',
            'issuedDate' => '2025-10-10',
            'name' => 'Иван',
            'nameLatin' => 'Ivan',
            'noPassportNumber' => false,
            'noPassportSeries' => false,
            'noPatronymic' => false,
            'passportNumber' => '1234',
            'passportSeries' => 'AB',
            'patronymic' => 'Иванович',
            'patronymicLatin' => 'Ivanovich',
            'phone' => '89346573385',
            'surname' => 'Иванов',
            'surnameLatin' => 'Ivanov',
            'noPatronymicLatin' => false,
            'passportScan' => UploadedFile::fake()->image('passport.pdf'),
            'passportTranslateScan' => UploadedFile::fake()->image('passport_translate.pdf'),
        ], $overrides);
    }

    protected function postForeignNational(array $overrides = [])
    {
        return $this->actingAs($this->user)
            ->postJson('/foreign-nationals', $this->foreignNationalBody($overrides));
    }
        
    public function test_success(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->postForeignNational();

        $response->assertOk();
        $this->assertDatabaseCount('foreign_nationals',1);
        
    }

    public function test_fail_under_18(): void
    {
        $response = $this->postForeignNational([
            'dateBirth' => Carbon::now()->subYears(18)->addDay()->format('Y-m-d'),
        ]);

        $response->assertBadRequest();
        $this->assertDatabaseEmpty('foreign_nationals');
    }

    public function test_success_no_patronymic(): void
    {
        $response = $this->postForeignNational([
            'patronymic' => '',
            'noPatronymic' => true,
        ]);
        $response->assertOk();
        $this->assertDatabaseCount('foreign_nationals',1);
    }

    public function test_success_no_patronymic_latin(): void
    {
        $response = $this->postForeignNational([
            'patronymicLatin' => '',
            'noPatronymicLatin' => true,
        ]);
        $response->assertOk();
        $this->assertDatabaseCount('foreign_nationals',1);
    }


    public function test_success_no_passport_number(): void
    {
        $response = $this->postForeignNational([
            'passportNumber' => '',
            'noPassportNumber' => true,
        ]);

        $response->assertOk();
        $this->assertDatabaseCount('foreign_nationals',1);
    }

    public function test_success_no_passport_series(): void
    {
        $response = $this->postForeignNational([
            'passportSeries' => '',
            'noPassportSeries' => true,
        ]);

        $response->assertOk();
        $this->assertDatabaseCount('foreign_nationals',1);
    }
    public function test_fail_with_passport_number_when_must_not_be(): void
    {
        $response = $this->postForeignNational([
            'passportNumber' => 'erterter',
            'noPassportNumber' => true,
        ]);

        $response->assertInertiaFlashMissing('success');
        $this->assertDatabaseEmpty('foreign_nationals');
    }
}
