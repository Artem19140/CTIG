<?php

    namespace Tests\Feature\Exam\Manage;

    use App\Enums\UserRoles;
    use App\Models\Address;
    use App\Models\ExamType;
    use App\Models\Center;
    use App\Models\Role;
    use App\Models\User;
    use Carbon\Carbon;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
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
                ->post('/exams', $this->examBody($overrides));
        }

        protected function examBody(array $overrides = []){
            $fixedDate = Carbon::now()->format('Y-m-d');
            $fixedTime = Carbon::now($this->user->time_zone)->addHour()->format('H:i');
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
            // dump(Carbon::now($this->user->time_zone)->addHour()->utc()->format('H:i'));
            // dd(Carbon::now()->format('H:i'));
            $response = $this->postExam();
            
            $response->assertInertiaFlash('success');

            $this->assertDatabaseCount('exams', 1);
        }

        public function test_success_different_addresses(): void
        {
            $this->withoutExceptionHandling();
            $response = $this->postExam();
            $response->assertInertiaFlash('success');

            $examiner = User::factory()->create();
            $examiner->roles()->attach($this->examinerRole);

            $address = Address::factory()->create();
            $response = $this->postExam([
                'addressId' => $address->id,
                'examiners' => [$examiner->id],
                'capacity' => $address->max_capacity,
            ]);
            $response->assertInertiaFlash('success');
            $this->assertDatabaseCount('exams', 2);
        }
    
        public function test_fail_in_past(): void
        {
            $pastDateTime = Carbon::now($this->user->time_zone)->subHour();
            $response = $this->postExam([
                'date' => $pastDateTime->format('Y-m-d'),
                'time' => $pastDateTime->subHour()->format('H:i')
            ]);
            
            $response->assertBadRequest();

            $this->assertDatabaseEmpty('exams');
        }

        public function test_fail_with_busy_tester(): void
        {
            $this->withoutExceptionHandling();
            $fixedTime = Carbon::now()->addHour()->format('H:i');
            
            $response = $this->postExam(['time' => $fixedTime]);
            $response->assertInertiaFlash('success');

            $response = $this->postExam(['time' => $fixedTime]);
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
            $response->assertBadRequest();
            $response->assertInertiaFlashMissing('success');
            $this->assertDatabaseEmpty('exams');
        }

        public function test_fail_not_active_adress(): void
        {   
            $address = Address::factory()->create(['is_active' => false]);
            $response = $this->postExam([
                'addressId' => $address->id
            ]);
            $response->assertBadRequest();
            $response->assertInertiaFlashMissing('success');
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
            $response->assertInertiaFlashMissing('success');
            $this->assertDatabaseEmpty('exams');
        }

    }
