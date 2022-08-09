<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'desc' => $this->faker->address,
            'image' => 'https://storage.googleapis.com/duy-demo/image-' . rand(1, 20) . '.png',
            'content' => $this->faker->text,
        ];
    }
}
