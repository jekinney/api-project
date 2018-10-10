<?php

namespace App\Blog;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
	* Specificly set table
	*
	* @var string
	*/
    protected $table = 'blog_categories';

    /**
    * Guarded columns from mass assignment
    *
    * @var array
    */
    protected $guarded = [];

    ///// Relationships

    /**
    * get all the assigned articles
    */
    public function articles()	
    {
    	return $this->hasMany( Article::class );
    }

    ///// Queries

    /**
    * Get a menu list of categories with all data
    *
    * @return Collection
    */
    public function menuList()
    {
    	return $this->ordered()->select( 'id', 'name' )->withCount( 'articles' )->get();
    }

    /**
    * Get a full list of categories with all data
    *
    * @return Collection
    */
    public function fullList()
    {
    	return $this->ordered()->withCount( 'articles' )->with( 'articles' )->get();
    }

    /**
    * Get a select list of categories for drop down or select list.
    *
    * @return Collection
    */
    public function selectList()
    {
    	return $this->ordered()->get( ['id', 'name'] );
    }

    /**
    * Get a detailed list with article count of categories.
    *
    * @return Collection
    */
    public function detailedList()
    {
    	return $this->ordered()->withCount( 'articles' )->get();
    }

    /**
    * Get a single category to show or edit. 
    * Allowed param to load articles
    *
    * @param mixed $identifier
    * @param \illuminate\Http\Request
    * @return Model
    */
    public function show(Request $request)
    {
    	$category = $this->findbyIdentifier( $request->identifier, true );

    	if ( $request->articles ) {

    		return $category->load( 'articles' );

    	}

    	return $category;
    }

    /**
    * Create a category and insert into database
    *
    * @param \Illuminate\Http\Request
    * @return Model | Collection
    */
    public function store(Request $request)
    {
    	// Validate
    	$this->validateInput( $request );

    	// Insert
    	$category = $this->create( $this->setData($request) );

    	return $this->returnType( $category, $request->return );
    }

    /**
    * Update a category and insert updates into database
    *
    * @param \Illuminate\Http\Request
    * @return Model | Collection
    */
    public function renew(Request $request)
    {
    	$category = $this->findbyIdentifier( $request->identifier, true );

    	// Validate
    	$this->validateInput( $request );

    	// Insert
    	$category->update( $this->setData($request) );

    	// Check for return type if any
    	return $this->returnType( $category->fresh(), $request->return );
    }

    /**
    * Remove a category IF it has no articles
    *
    * @param int | string $identifier
    * @return boolean
    */
    public function remove($identifier)
    {
    	$category = $this->findbyIdentifier( $identifier, true );

    	if ( $category->articles_count > 0 ) {

    		// 409 Conflict
    		return abort( 409, 'Unable to remove category when articles are still attached.' );

    	}

    	return $category->delete();
    }

    ///// Helpers

    /**
    * Query to fond or fail a single
    * category and count of articles
    * if requested 
    *
    * @param int | string $identifier
    * @param boolean $count
    * @return Model
    */
    private function findbyIdentifier($identifier, $count = false)
    {
    	if ( $count ) {

    		return $this->withCount( 'articles' )
    		->where( 'id', $identifier )
    		->orWhere( 'slug', $identifier )
    		->firstOrFail();

    	}

    	return $this->where( 'id', $identifier )
    		->orWhere( 'slug', $identifier )
    		->firstOrFail();
    }

    /**
    * Set, manipulate and strip as needed input data
    *
    * @param \Illuminate\Http\Request $request
    * @return array
    */
    private function setData(Request $request)
    {
        return [
            'name' => $request->name,
            'slug' => str_slug( $request->name ),
            'description' => $request->description,
        ];
    }

    /**
    * Validate input
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Request
    */
    private function validateInput(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:122|unique:blog_categories,name',
            'description' => 'required|string|max:225',
        ];

        if ( $request->isMethod('patch') ) {

            $rules['name'] .= ','.$id;

        }

        return $request->validate( $rules );
    }

    private function ordered()
    {
        return $this->orderBy( 'name', 'asc' );
    }

    /**
    * On store and update we can return the 
    * specific model or a new collection
    *
    * @param \App\Blog\Category $category
    * @param string $return
    * @return Model|collection
    */
    private function returnType($category, $return = null)
    {
    	// Check for return type if any
    	if ( $return && $return === 'all' ) {

    		return $this->fullList();

    	}

    	return $category;
    }
}
