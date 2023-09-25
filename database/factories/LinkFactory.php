<?php

namespace Fasaya\UrlShortener\Factories;

use Fasaya\UrlShortener\Model\Link;
use Illuminate\Database\Eloquent\Factories\Factory;


class LinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Link::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'slug' => $this->faker->slug,
            'short_url' => $this->faker->url,
            'long_url' => $this->faker->url,
            'clicks' => $this->faker->numberBetween(0, 1000), // Adjust the range as needed
            'is_disabled' => $this->faker->boolean,
        ];
    }
}
