<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Examination;
use App\Models\UserExamination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
// use PHPUnit\Framework\Attributes\DataProvider;
// use PHPUnit\Framework\Attributes\TestWith;

class UserExaminationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpExaminations();
        $this->user = User::factory()->create();
    }

    private function setUpExaminations(): void
    {
        Examination::factory()->count(10)->create(['enabled' => 1]);
    }

    #[Test]
    public function プレイヤー試験ページの表示(): void
    {
        $response = $this->actingAs($this->user)->get(route('user-examination.start'));
        $response->assertOk();
    }

    #[Test]
    public function プレイヤー試験の作成(): void
    {
        $response = $this->actingAs($this->user)->post(
            route('user-examination.store', [
                'exam' => env('ITEMS_PER_EXAM'),
                'score' => (int)ceil(env('ITEMS_PER_EXAM') * env('PASSING_RATE')),
            ])
        );
        $response->assertRedirect(
            route('user-examination.select', ['user_examination' => 1])
        );
    }
}
