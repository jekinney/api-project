<?php

namespace App\Http\Controllers\Blog;

use App\Blog\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\Blog\{
    ArticleResource,
    ArticleCollection
};

class ArticleController extends Controller
{
    /**
    * Article property
    */
    protected $article;

    /**
    * Constructor:
    * Make a new article instance avalible
    */
    function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function top(Request $request)
    {
        return new ArticleCollection( $this->article->topList($request) );
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function full(Request $request)
    {
        return new ArticleCollection( $this->article->fullList($request) );
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paginated(Request $request)
    {
        return new ArticleCollection( $this->article->paginatedList($request) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return new ArticleResource( $this->article->store($request) );
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Http\Response
     */
    public function show($identifier)
    {
        return new ArticleResource( $this->article->show($identifier) );
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
        $request->merge( compact('identifier') );

        return new ArticleResource( $this->article->renew($request) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Http\Response
     */
    public function destroy($identifier)
    {
        $this->article->remove( $identifier );

        return response()->json( ['success' => 'Article has been removed'], 201 );
    }
}
