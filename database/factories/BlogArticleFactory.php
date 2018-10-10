<?php

use Carbon\Carbon;
use App\Blog\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {

	$body = '';

	foreach ( $faker->paragraphs( 10 ) as $para ) {

		$body .= '<p>'. $para .'</p>';

	}

    return [
    	'body' => json_encode( $body ),
        'slug' => str_slug( $faker->sentence ),
        'title' => $faker->sentence,
        'user_id' => factory( \App\User::class )->create()->id,
        'overview' => $faker->sentence,
        'publish_at' => Carbon::now()->subMonth(),
        'category_id' => factory( \App\Blog\Category::class )->create()->id,
    ];
});
