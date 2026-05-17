<?php

namespace Tests\Feature\Attempt;

use App\Domain\Attempt\Action\CloseAbandonedAttemptsAction;
use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\ExamType;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CloseAbandonedAttemptsTest extends TestCase
{
    use RefreshDatabase;
    protected CloseAbandonedAttemptsAction $action;
    protected function setUp():void{
        parent::setUp();
        $this->action = app(CloseAbandonedAttemptsAction::class);
        Carbon::setTestNow('2026-01-01 10:00:00');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); 
    }

    public function test_close_attempt_no_auto_finish(): void{
        $examType = ExamType::factory()->create(['need_human_check' => true]);
        $exam = Exam::factory()->create(['exam_type_id' => $examType]);
        $attempt = Attempt::factory()
            ->acive()
            ->create([
                'exam_id' => $exam->id,
                'expired_at' => '2026-01-01 09:59:59',
                'last_activity_at' => '2026-01-01 09:50:00'
            ]);

        $this->action->execute();

        $this->assertDatabaseHas('attempts', [
            'id' => $attempt->id,
            'status' => AttemptStatus::Finished,
            'expired_at' => '2026-01-01 09:59:59',
            'last_activity_at' => '2026-01-01 09:50:00',
            'finished_at' => '2026-01-01 09:50:00'
        ]);

        $attempt->refresh();
    }

    public function test_close_attempt_auto_finish(): void{
        $examType = ExamType::factory()
            ->create([
                'need_human_check' => false
            ]);
            
        $exam = Exam::factory()
            ->create([
                'exam_type_id' => $examType
            ]);

        $attempt = Attempt::factory()
            ->acive()
            ->create([
                'exam_id' => $exam->id,
                'expired_at' => '2026-01-01 09:59:59',
                'last_activity_at' => '2026-01-01 09:50:00'
            ]);
        $this->action->execute();

        $this->assertDatabaseHas('attempts', [
            'id' => $attempt->id,
            'status' => AttemptStatus::Checked,
            'expired_at' => '2026-01-01 09:59:59',
            'last_activity_at' => '2026-01-01 09:50:00',
            'finished_at' => '2026-01-01 09:50:00'
        ]);
        $attempt->refresh();
    }

    public function test_not_close_not_expired_attempt(): void{
        $attempt = Attempt::factory()
            ->acive()
            ->create([
                'expired_at' => '2026-01-01 10:00:01',
                'last_activity_at' => '2026-01-01 09:50:00'
            ]);

        $this->action->execute();
        $this->assertDatabaseHas('attempts', [
            'id' => $attempt->id,
            'status' => $attempt->status
        ]);
        $attempt->refresh();

        $this->assertNull($attempt->finished_at);
        $this->assertNull($attempt->checked_at);
    }

     public function test_not_close_banned_attempt(): void{
        $attempt = Attempt::factory()
            ->banned()
            ->create([
                'expired_at' => '2026-01-01 10:00:01',
                'last_activity_at' => '2026-01-01 09:50:00'
            ]);

        $this->action->execute();

        $this->assertDatabaseHas('attempts', [
            'id' => $attempt->id,
            'status' => AttemptStatus::Banned
        ]);

        $attempt->refresh();
        
        $this->assertNotNull($attempt->banned_at);
        $this->assertNull($attempt->finished_at);
        $this->assertNull($attempt->checked_at);
    }
}
