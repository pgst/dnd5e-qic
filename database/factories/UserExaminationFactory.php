<?php

namespace Database\Factories;

use App\Models\UserExamination;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserExamination>
 */
class UserExaminationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'examination_id' => \App\Models\Examination::factory(),
            'enabled' => 1,
            'cleared' => 0,
            'challenge_num' => 1,
            'question_num' => 0,
        ];
    }
}
