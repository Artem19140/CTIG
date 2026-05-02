<?php

namespace Tests\Feature\Exam;

use App\Domain\Exam\Guard\ExamEnrollmentGuard;
use App\Exceptions\BusinessException;
use App\Models\Center;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamEnrollmentGuardTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected $action;
    protected $exception;
     protected function setUp():void{
        parent::setUp();
        $this->action = app(ExamEnrollmentGuard::class);
        $this->exception = BusinessException::class;
        Carbon::setTestNow(Carbon::now());
    }
    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }

    public function test_success_ensure_has_enrollment(){
        $exam = Exam::factory()
            ->hasAttached(
                ForeignNational::factory()->count(3),
                [
                    'reg_number'=>124,
                    'creator_id' => User::factory()->create()->id, 
                    'has_payment'=> true,
                    'center_id' => Center::factory()->create()->id
                ]
            )->create();
        $this->action->ensureEnrollmentsExists($exam);
        $this->assertTrue(true);
    }

    public function test_fail_ensure_has_enrollment(){
        $this->expectException($this->exception);
        $exam = Exam::factory()->create();
        $this->action->ensureEnrollmentsExists($exam);
    }

    public function test_fail_ensure_has_parrallel_enrollments(){
        
        $this->expectException($this->exception);

        $foreignNational = ForeignNational::factory()->create();

        $time = Carbon::now();

        $exam = Exam::factory()
            ->create(['begin_time' => $time]);

        Enrollment::factory()->create([
            'foreign_national_id' => $foreignNational->id,
            'exam_id' => $exam->id
        ]);
        
        $this->action->ensureEnrollmentNotExists($exam, $foreignNational);
    }
}
