<?php

    namespace Tests\Feature\Exam\Manage;

    use App\Enums\UserRoles;
    use App\Models\Address;
    use App\Models\ExamType;
    use App\Models\Organization;
    use App\Models\Role;
    use App\Models\User;
    use Carbon\Carbon;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Tests\TestCase;
    use Inertia\Testing\AssertableInertia as Assert;

    class ExamCreateTest extends TestCase
    {
        use RefreshDatabase;
        /**
         * A basic feature test example.
         */
        protected User $user;
        protected ExamType $examType;
        protected Address $address;
        protected Role $examinerRole;
        protected function setUp():void{
            parent::setUp();
            Organization::factory()->create();
            $this->user = User::factory()->create();
            $this->examinerRole = Role::create([
                'name' => UserRoles::Examiner
            ]);
            $this->user->roles()->attach($this->examinerRole);
            $this->examType = ExamType::factory()->create();
            $this->address = Address::factory()->create();
            Carbon::setTestNow(now());
        }

        protected function postExam(array $overrides = []){
            return $this->actingAs($this->user)
                ->post('/exams', $this->examBody($overrides));
        }

        protected function examBody(array $overrides = []){
            return array_merge([
                'date' =>Carbon::now()->format('Y-m-d'),
                'time' => Carbon::now($this->user->organization->time_zone)->addHour()->format('H:i'),
                'examTypeId' => $this->examType->id,
                'addressId'=> $this->address->id,
                'capacity' => $this->address->max_capacity,
                'examiners' => [$this->user->id],
                'comment' => ''
            ], $overrides);
        }

        public function test_success(): void
        {
            $this->withoutExceptionHandling();

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
            $response = $this->postExam([
                'time' => Carbon::now($this->user->organization->time_zone)->subHour()->format('H:i')
            ]);
            
            $response->assertBadRequest();

            $this->assertDatabaseEmpty('exams');
        }

        public function test_fail_with_busy_tester(): void
        {
            $response = $this->postExam();
            $response->assertInertiaFlash('success');

            $response = $this->postExam();
            $response->assertBadRequest();

            $this->assertDatabaseCount('exams', 1);
        }

        public function test_fail_with_no_role_examiner(): void
        {   
            $examiner = User::factory()->create();
            $response = $this->postExam([
                'examiners' => [$examiner->id]
            ]);
            $response->assertBadRequest();
            $this->assertDatabaseEmpty('exams');
        }

        public function test_fail_more_than_max_capacity(): void
        {   
            $response = $this->postExam([
                'capacity' => $this->address->max_capacity + 1
            ]);
            $response->assertBadRequest();
            $this->assertDatabaseEmpty('exams');
        }
        public function test_fail_less_zero_capacity(): void
        {   
            $response = $this->postExam([
                'capacity' => -1
            ]);
            $response->assertInertiaFlashMissing('success');
            $this->assertDatabaseEmpty('exams');
        }

        public function test_fail_with_zero_capacity(): void
        {   
            $response = $this->postExam([
                'capacity' => 0
            ]);
            $response->assertInertiaFlashMissing('success');
            $this->assertDatabaseEmpty('exams');
        }

        public function test_fail_with_empty_params(): void
        {   
            $response = $this->postExam([
                'date' =>'',
                'time' => '',
                'examTypeId' => '',
                'addressId'=> '',
                'capacity' => '',
                'examiners' => '',
                'comment' => ''
            ]);
            $response->assertInertiaFlashMissing('success');
            $this->assertDatabaseEmpty('exams');
        }

        public function test_fail_with_negative_number_params(): void
        {   
            $response = $this->postExam([
                'examTypeId' => -1,
                'addressId'=> -1,
                'capacity' => -1,
            ]);
            $response->assertInertiaFlashMissing('success');
            $this->assertDatabaseEmpty('exams');
        }

        public function test_fail_with_zero_number_params(): void
        {   
            $response = $this->postExam([
                'examTypeId' => 0,
                'addressId'=> 0,
                'capacity' => 0,
            ]);
            $response->assertInertiaFlashMissing('success');
            $this->assertDatabaseEmpty('exams');
        }
    }
