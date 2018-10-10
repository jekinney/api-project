<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Blog\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogCategoryTest extends TestCase
{
	use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function get_a_list_for_menu()
    {
    	factory( Category::class, 10 )->create();

    	$resposne = $this->json( 'GET', '/api/blog/category/list/menu' );

        $response->assertStatus( 200 );
    }
}
