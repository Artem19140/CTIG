<?php

namespace Tests\Feature\Attempt;

use App\Domain\Attempt\Action\CloseAbandonedAttemptsAction;
use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\ExamType;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CloseAbandonedAttemptsTest extends TestCase
{
    use RefreshDatabase;
    protected CloseAbandonedAttemptsAction $action;
    protected function setUp():void{
        parent::setUp();
        $this->action = app(CloseAbandonedAttemptsAction::class);
        Carbon::setTestNow(Carbon::now());
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
            ->expired()
            ->acive()
            ->create(['exam_id' => $exam->id]);
        $this->action->execute();
        $this->assertDatabaseHas('attempts', [
            'id' => $attempt->id,
            'status' => AttemptStatus::Finished
        ]);
        $attempt->refresh();

        $this->assertNotNull($attempt->finished_at);
    }

    public function test_close_attempt_auto_finish(): void{
        $examType = ExamType::factory()->create(['need_human_check' => false]);
        $exam = Exam::factory()->create(['exam_type_id' => $examType]);
        $attempt = Attempt::factory()
            ->expired()
            ->acive()
            ->create(['exam_id' => $exam->id]);
        $this->action->execute();

        $this->assertDatabaseHas('attempts', [
            'id' => $attempt->id,
            'status' => AttemptStatus::Checked
        ]);
        $attempt->refresh();

        $this->assertNotNull($attempt->finished_at);
        $this->assertNotNull($attempt->checked_at);
    }

    public function test_not_close_not_expired_attempt(): void{
        $attempt = Attempt::factory()
            ->notExpired()
            ->acive()
            ->create();
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
            ->notExpired()
            ->banned()
            ->create();

        $this->action->execute();

        $this->assertDatabaseHas('attempts', [
            'id' => $attempt->id,
            'status' => $attempt->status
        ]);

        $attempt->refresh();
        
        $this->assertNotNull($attempt->banned_at);
        $this->assertNull($attempt->finished_at);
        $this->assertNull($attempt->checked_at);
    }
}
