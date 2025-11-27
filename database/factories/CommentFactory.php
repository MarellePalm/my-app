<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Comment;
use App\Models\User;
use App\Models\Post;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            // NB: see loob vajadusel uue useri ja uue postituse
            'user_id' => User::factory(),
            'post_id' => Post::factory(),
            'content' => $this->faker->sentence(12),
        ];
    }
}
