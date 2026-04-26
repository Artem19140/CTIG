<?php

namespace Tests\Feature\Counter;


use App\Domain\Counter\GenerateGroupNumberAction;
use App\Enums\CounterKey;
use App\Models\Counter;
use App\Models\Center;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetGroupNumberTest extends TestCase
{
    use RefreshDatabase;
    protected $action;
    protected Counter $counter;
    protected int $value;
    protected function setUp():void{
        parent::setUp();
        
        $this->action = app(GenerateGroupNumberAction::class);
        Carbon::setTestNow(Carbon::now());
        $this->value = 0;
        $this->counter = Counter::create([
            'key' => CounterKey::GroupKey,
            'value' => $this->value,
            'center_id' => Center::factory()->create()->id
        ]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); // сброс фиксации
    }

    public function test_success(): void
    {
       $groupNumber = $this->action->execute();
       $this->assertEquals($groupNumber, 1);
    }

    public function test_two_time(): void
    {
       $groupNumber = $this->action->execute();
       $this->assertEquals($groupNumber, 1);
       $groupNumber = $this->action->execute();
       $this->assertEquals($groupNumber, 2);
    }

    public function test_change_day(): void
    {
        $groupNumber = $this->action->execute();
        $this->assertEquals($groupNumber, 1);
        Carbon::setTestNow(Carbon::now()->addDay());
        $this->action = app(GenerateGroupNumberAction::class);
        $groupNumber = $this->action->execute();
        $this->assertEquals($groupNumber, 1);
        $groupNumber = $this->action->execute();
        $this->assertEquals($groupNumber, 2);
        Carbon::setTestNow();
    }
}
