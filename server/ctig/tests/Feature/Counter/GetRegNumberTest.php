<?php

namespace Tests\Feature\Counter;

use App\Actions\Counter\GetRegNumberAction;
use App\Enums\CounterKey;
use App\Models\Counter;
use App\Models\Center;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;
use Tests\TestCase;

class GetRegNumberTest extends TestCase
{
    use RefreshDatabase;
    protected $action;
    protected int $value;
    protected Counter $counter;
    protected function setUp():void{
        parent::setUp();
        
        $this->action = app(GetRegNumberAction::class);
        Carbon::setTestNow(Carbon::now());
        $this->value = \intval(Carbon::now()->format('y0000'));
        $this->counter = Counter::create([
            'key' => CounterKey::RegNumKey,
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
        $regNumber = $this->action->execute();
        $this->assertEquals($regNumber, \intval(Carbon::now()->format('y0000') + 1));
    }

    public function test_change_year(): void
    {
        $regNumber = $this->action->execute();
        $this->assertEquals($regNumber, \intval(Carbon::now()->format('y0000') + 1));
        Carbon::setTestNow(Carbon::now()->addYear());
        $regNumber = $this->action->execute();
        $this->assertEquals($regNumber, \intval(Carbon::now()->format('y0000') + 1));
        $regNumber = $this->action->execute();
        $this->assertEquals($regNumber, \intval(Carbon::now()->format('y0000') + 2));
        Carbon::setTestNow(Carbon::now());
    }
}
