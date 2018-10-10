<?php

use Faker\Generator as Faker;

$factory->define(App\Blog\Category::class, function (Faker $faker) {
    return [
        'slug' => str_slug( $faker->word ),
        'name' => $faker->word,
        'description' => $faker->sentence,
    ];
});
