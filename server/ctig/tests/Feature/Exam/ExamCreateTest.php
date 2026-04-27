<?php

    namespace Tests\Feature\Exam;

    use App\Enums\UserRoles;
    use App\Models\Address;
    use App\Models\Exam;
    use App\Models\ExamType;
    use App\Models\Center;
    use App\Models\Role;
    use App\Models\User;
    use Carbon\Carbon;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Log;
    use Tests\TestCase;

    class ExamCreateTest extends TestCase
    {
        use RefreshDatabase;

        protected User $user;
        protected ExamType $examType;
        protected Address $address;
        protected Role $examinerRole;
        protected User $examiner;
        protected function setUp():void{
            parent::setUp();
            $center = Center::factory()->create();
            
            $this->examinerRole = Role::create([
                'name' => UserRoles::Examiner,
            ]);

            $schedulerRole = Role::create([
                'name' => UserRoles::Scheduler,
            ]);

            $this->examiner = User::factory()->create(['center_id' => $center->id]);
            $this->examiner->roles()->attach($this->examinerRole);

            $this->user = User::factory()->create(['center_id' => $center->id]);
            $this->user->roles()->attach($schedulerRole);
            $this->examType = ExamType::factory()->create();
            $this->address = Address::factory()->create(['center_id' => $center->id]);

            Carbon::setTestNow(
                Carbon::now()
            );
        }

        public function tearDown(): void
        {
            parent::tearDown();
            Carbon::setTestNow(); // сброс фиксации
        }

        protected function postExam(array $overrides = []){
            return $this->actingAs($this->user)
                ->postJson('/exams', $this->examBody($overrides));
        }

        protected function examBody(array $overrides = []){
            $fixedDate = Carbon::now($this->user->time_zone)->format('Y-m-d');
            $fixedTime = Carbon::now($this->user->time_zone)->addHours(Exam::CREATE_AVAILABLE_BEFORE_HOURS)->addMinute()->format('H:i');
            Log::info($fixedTime);
            return array_merge([
                'date' =>$fixedDate,
                'time' => $fixedTime,
                'examTypeId' => $this->examType->id,
                'addressId'=> $this->address->id,
                'capacity' => $this->address->max_capacity,
                'examiners' => [$this->examiner->id],
                'comment' => ''
            ], $overrides);
        }

        public function test_success(): void
        {
            $this->withoutExceptionHandling();
            $response = $this->postExam();
            
            $response->assertOk();

            $this->assertDatabaseCount('exams', 1);
        }

        public function test_success_different_addresses(): void
        {
            $response = $this->postExam();
            $response->assertOk();
            $examiner = User::factory()->create();
            $examiner->roles()->attach($this->examinerRole);

            $address = Address::factory()->create();
            $response = $this->postExam([
                'addressId' => $address->id,
                'examiners' => [$examiner->id],
                'capacity' => $address->max_capacity,
            ]);
            $response->assertOk();
            $this->assertDatabaseCount('exams', 2);
        }
    
        public function test_fail_in_past(): void
        {
            $pastDateTime = Carbon::now($this->user->time_zone)->subHour();

            $response = $this->postExam([
                'date' => $pastDateTime->format('Y-m-d'),
                'time' => $pastDateTime->addHours(Exam::CREATE_AVAILABLE_BEFORE_HOURS)->subMinute()->format('H:i')
            ]);
            
            $response->assertUnprocessable();

            $this->assertDatabaseEmpty('exams');
        }

        public function test_fail_with_busy_tester(): void
        {

            
            $response = $this->postExam();
            $response->assertOk();

            // $response = $this->postExam();
            $address = Address::factory()->create();
            $response = $this->postExam([
                'addressId' => $address->id,
                'capacity' => $address->max_capacity
            ]);
            $response->assertBadRequest();

            $this->assertDatabaseCount('exams', 1);
        }

        public function test_fail_with_no_role_examiner(): void
        {   
            $response = $this->postExam([
                'examiners' => [$this->user->id]
            ]);
            $response->assertBadRequest();
            $this->assertDatabaseEmpty('exams');
        }

        // public function test_fail_with_no_role_scheduler(): void
        // {   
        //     $this->actingAs($this->examiner)
        //         ->post('/exams', $this->examBody());

        //     $this->assertDatabaseEmpty('exams');
        // }

        public function test_fail_more_than_max_capacity(): void
        {   
            $response = $this->postExam([
                'capacity' => $this->address->max_capacity + 1
            ]);
            $response->assertUnprocessable();
            $this->assertDatabaseEmpty('exams');
        }

        public function test_fail_not_active_address(): void
        {   
            $address = Address::factory()->create(['is_active' => false]);
            $response = $this->postExam([
                'addressId' => $address->id
            ]);
            $response->assertUnprocessable();
            $this->assertDatabaseEmpty('exams');
        }

        public function test_fail_not_active_examiner(): void
        {   
            $user = User::factory()->create(['is_active' => false]);
            $user->roles()->attach($this->examinerRole);
            $response = $this->postExam([
                'examiners' => [$user->id]
            ]);
            $response->assertBadRequest();
            $this->assertDatabaseEmpty('exams');
        }

    }
