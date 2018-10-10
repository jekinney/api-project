<?php

namespace App\Blog;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{   
    ///// Set up and overides

    /**
    * Always eager load relationships
    *
    * @var array
    */
    protected $with = ['author', 'category'];

    /**
    * Set additional timestamp columns
    *
    * @var array
    */
    protected $dates = ['publish_at'];
    
	/**
	* Specificly set table
	*
	* @var string
	*/
    protected $table = 'blog_articles';

    /**
    * Guarded columns from mass assignment
    *
    * @var array
    */
    protected $guarded = [];

    ///// Relationships

    /**
    * get the articles author's name
    */
    public function author()	
    {
    	return $this->belongsTo( \App\User::class, 'user_id', 'id' )
            ->select( 'id', 'name' );
    }

    /**
    * Get the article's category
    */
    public function category()
    {
    	return $this->belongsTo( \App\Blog\Category::class, 'category_id', 'id' )
            ->select( 'id', 'name' );
    }

    ///// Queries

    /**
    * Top list of newest articles
    *
    * @param \Illuminate\Http\Request $request
    * @return Collection
    */
    public function topList(Request $request)
    {
       return $this->findPublished()
            ->limit( $request->limit?? 10 )
            ->get( $this->withOverview() );
    }

    /**
    * Full list of all articles 
    *
    * @param \Illuminate\Http\Request $request
    * @return Collection
    */
    public function fullList(Request $request)
    {
        $limit = $request->limit?? 10;
        $order = $request->order?? 'publish_at';
        $direction = $request->direction?? 'desc';

        return $this->filterList( $request->filter )
                ->orderBy( $order, $direction )
                ->paginate( $limit );
    }

    /**
    * Paginated of articles for pagination 
    *
    * @param \Illuminate\Http\Request $request
    * @return Collection
    */
    public function paginatedList(Request $request)
    {
        return $this->where( 'publish_at', '<', Carbon::now() )
            ->orderBy( 'publish_at', 'desc' )
            ->paginate( $request->limit?? 10, $this->withOverview() );
    }

    /**
    * Get a single article and data
    *
    * @param int | String $identifier
    * @return Model
    */
    public function show($identifier)
    {
        // Need to check published at and user perms
        return $this->findByIdentifier( $identifier );
    }

    /**
    * Create a new article and assigen 
    * auth user as articles author
    *
    * @param \Illuminate\Http\Request $request
    * @return Model
    */
    public function store(Request $request)
    {
        // Validate request input
        $this->validateInput( $request );

        // store the new article after we set data
        return $this->create( $this->setData($request) );
    }

    /**
    * Update an existing article
    *
    * @param int | string $identifier
    * @param \Illuminate\Http\Request $request
    * @return Model
    */
    public function renew(Request $request)
    {
        // Find article
        $article = $this->findByIdentifier( $request->identifier );

        // Validate request
        $this->validateInput( $request, $article->id );

        // Merge in user id to preserve original author
        $request->merge(['user_id' => $article->user_id]);

        // Update after we set data
        $article->update( $this->setData($request) );

        // return the updated model
        return $article->fresh();
    }

    /**
    * Perminatly remove an article 
    *
    * @param int | string $identifier
    * @return boolean
    */
    public function remove($identifier)
    {
        return $this->findByIdentifier( $identifier )->delete();
    }

    /**
    * Find a single article either by slug or id
    *
    * @param mixed $idendifier
    * @return Model
    */
    private function findByIdentifier($identifier)
    {
        return $this->where( 'id', $identifier )
            ->orWhere( 'slug', $identifier )
            ->firstOrFail();
    } 

    /**
    * Basic set up for published article queries
    *
    * @return \Illuminate\Database\Eloquent\Builder
    */
    private function findPublished()
    {
        return $this->where( 'publish_at', '<', Carbon::now() )->orderBy( 'publish_at', 'desc' );
    }  

    /**
    * Filter results for the full list query
    *
    * @param string $filter
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function filterList($filter = 'all')
    {
        if ( $filter !== 'all' ) {

            if ( $filter === 'unpublished' ) {

                return $this->where( 'publish_at', '>', Carbon::now() );
                        
            } elseif ( $filter === 'published' ) {
                
                return $this->where( 'publish_at', '<', Carbon::now() );

            }
        }

        return $this;
    }

    ///// Helpers

    /**
    * Array of columns from base query for overview of an article(s)
    *
    * @return array
    */
    private function withOverview()
    {
        return ['id', 'slug', 'title', 'user_id', 'overview', 'category_id', 'publish_at'];
    }

    /**
    * Validate input for post and patch requests
    *
    * @param \Illuminate\Http\Request $request
    * @param int $id
    * @return \Illuminate\Http\Request
    */
    private function validateInput(Request $request, $id = null)
    {
        // basic rules array for both types
        $rules = [
            'body' => 'required|min:100',
            'title' => 'required|max:255|string|unique:blog_articles,title',
            'heading' => 'required_without:heading_url|image',
            'overview' => 'required|between:10,550|string',
            'publish_at' => 'required|date',
            'category_id' => 'required|integer|exists:blog_categories,id',
            'heading_url' => 'required_without:heading|string'
        ];

        // check if post of patch request, add title validatiogn as needed
        if ( $request->isMethod('PATCH') ) {
            
            $rules['title'] .= ','.$id;

        }

        // return validated request data
        $request->validate( $rules );
    }

    /**
    * Manipulate, escape and set data for insert into database
    *
    * @param \Illuminate\Http\Request $request
    * @return array
    */ 
    private function setData(Request $request)
    {
        return [
            'slug' => str_slug( $request->title ),
            'body' => json_encode( $request->body ),
            'title' => $request->title,
            'user_id' => $request->user_id?? $request->user()->id,
            'overview' => $request->overview,
            'publish_at' => Carbon::parse( $request->publish_at ),
            'category_id' => $request->category_id,
        ];
    }
}
