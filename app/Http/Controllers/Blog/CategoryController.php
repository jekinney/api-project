<?php

namespace App\Http\Controllers\Blog;

use App\Blog\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\Blog\{
    CategoryResource,
    CategoryCollection
};

class CategoryController extends Controller
{
    /**
    * Inject category
    */
    protected $category;

    /**
    * Constructor: 
    * Always load a new category model
    * Protected routes via middleware
    */
    function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Display a full listing from ctaegories.
     *
     * @return \Illuminate\Http\Response
     */
    public function full()
    {
        return new CategoryCollection( $this->category->fullList() );
    }

    /**
     * Display a menu listing from categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function menu()
    {
        return new CategoryCollection( $this->category->menuList() );
    }

    /**
     * Display a select listing from categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function select()
    {
        return new CategoryCollection( $this->category->selectList() );
    }

    /**
     * Display a detailed listing from categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function detailed()
    {
        return new CategoryCollection( $this->category->detailedList() );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return new CategoryResource( $this->category->store( $request ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $identifier)
    {
        $request = $request->merge( compact('identifier') );

        return new CategoryResource( $this->category->show($request) );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed $identifier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $identifier)
    {
        $request = $request->merge( compact('identifier') );

        return new CategoryResource( $this->category->renew($request) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Http\Response
     */
    public function destroy($identifier)
    {
        $this->category->remove( $identifier );

        return response()->json( ['success' => 'category has been removed'], 201 );
    }
}
