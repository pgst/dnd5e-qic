<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Models\UserExamination;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
// use PHPUnit\Framework\Attributes\DataProvider;
// use PHPUnit\Framework\Attributes\TestWith;

class UserExaminationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 
     * @return void
     */
    #[Test]
    public function プレイヤー試験ページの表示(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('user-examination.start'));
        $response->assertStatus(200);
    }
}
