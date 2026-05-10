<?php

namespace Tests\Feature\ForeignNational;

use App\Enums\CounterKey;
use App\Enums\UserRoles;
use App\Models\Counter;
use App\Models\Exam;
use App\Models\Center;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\Helpers\RolesAccessCheck;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ForeignNationalCreateTest extends TestCase
{
    use RefreshDatabase, RolesAccessCheck;
    protected User $user;
    protected Center $center;
    protected Exam $exam;

    protected function setUp():void{
        parent::setUp();
        $this->seed(RolesSeeder::class);
        $this->center = Center::factory()->create();

        $this->user = User::factory()->operator()->create(['center_id' =>$this->center->id]); 
        Carbon::setTestNow(now());
        $this->exam = Exam::factory()->create([
            'begin_time' => Carbon::now()->addDay(),
            'end_time' => Carbon::now()->addDay()->addMinutes(90),
            'center_id' => $this->user->center_id
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
        Carbon::setTestNow();
    }
    protected function foreignNationalBody(array $overrides = [])
    {
        return array_merge([
            'addressReg' => fake()->streetAddress,
            'citizenship' => 'UZ',
            'comment' => fake()->sentence(),
            'dateBirth' => '2005-10-10',
            'examId' => $this->exam->id,
            'gender' => 'M',
            'hasPayment' => false,
            'issuedBy' => 'МВД по УР',
            'issuedDate' => '2025-10-10',
            'name' => fake()->name,
            'nameLatin' => 'Ivan',
            'noPassportNumber' => false,
            'noPassportSeries' => false,
            'noPatronymic' => false,
            'passportNumber' => '1234',
            'passportSeries' => 'AB',
            'patronymic' => fake()->name,
            'patronymicLatin' => 'Ivanovich',
            'phone' => '89346573385',
            'surname' => fake()->name,
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

        $this->success($response);
        $this->assertDatabaseCount('foreign_nationals',1);
        
    }

    public function test_fail_under_18(): void
    {
        $response = $this->postForeignNational([
            'dateBirth' => Carbon::now()->subYears(18)->addDay()->format('Y-m-d'),
        ]);

        $response->assertUnprocessable();
        $this->assertDatabaseEmpty('foreign_nationals');
    }

    public function test_success_no_patronymic(): void
    {
        $response = $this->postForeignNational([
            'patronymic' => '',
            'noPatronymic' => true,
        ]);
        $this->success($response);
        $this->assertDatabaseCount('foreign_nationals',1);
    }

    public function test_success_no_patronymic_latin(): void
    {
        $response = $this->postForeignNational([
            'patronymicLatin' => '',
            'noPatronymicLatin' => true,
        ]);
        $this->success($response);
        $this->assertDatabaseCount('foreign_nationals',1);
    }


    public function test_success_no_passport_number(): void
    {
        $response = $this->postForeignNational([
            'passportNumber' => '',
            'noPassportNumber' => true,
        ]);

        $this->success($response);
        $this->assertDatabaseCount('foreign_nationals',1);
    }

    public function test_success_no_passport_series(): void
    {
        $response = $this->postForeignNational([
            'passportSeries' => '',
            'noPassportSeries' => true,
        ]);

        $this->success($response);
        $this->assertDatabaseCount('foreign_nationals',1);
    }
    public function test_success_with_passport_number(): void
    {
        $response = $this->postForeignNational([
            'passportNumber' => '',
            'noPassportNumber' => true,
        ]);
        $this->success($response);
        $this->assertDatabaseCount('foreign_nationals',1);
    }

    protected function success(TestResponse $response){
        $response->assertOk()
        ->assertJsonStructure([
            'redirectUrl'
        ]);
    }

    public function test_access_roles(){
        $this->accessRolesCheck(
            allowedRoles:[UserRoles::Operator],
            method:'POST',
            route: route('foreign-nationals.store'),
            data: fn () => $this->foreignNationalBody([
                'passportNumber' => fake()->unique()->regexify('[A-Za-z0-9]{10}')
            ]),
            expectedCode: 200,
            center:$this->center
        );
    }
}
