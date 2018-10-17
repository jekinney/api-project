<?php

namespace App\Http\Controllers\Site;

use App\Site\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\Site\{
    PageResource,
    PageCollection
};

class PageController extends Controller
{
    protected $page;

    /**
    * Ensure a new page instatce is ready
    */
    function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fullList()
    {
        return new PageCollection( $this->page->fullList() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return new PageResource( $this->page->store($request) );
    }

    /**
     * Display the specified resource.
     *
     * @param  string | int $identifier
     * @return \Illuminate\Http\Response
     */
    public function show($identifier)
    {
        return new PageResource( $this->page->show($identifier) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string | int $identifier
     * @return \Illuminate\Http\Response
     */
    public function edit($identifier)
    {
        return new PageResource( $this->page->edit($identifier) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string | int $identifier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $identifier)
    {
        return new PageResource( $this->page->renew($request) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string | int $identifier
     * @return \Illuminate\Http\Response
     */
    public function destroy($identifier)
    {
        if ( $this->page->remoove($identifier) ) {

            return response()->json(['success', 'Page has been removed']);
        }

        return response()->json(['error', 'Unable to remove the page at this time']);
    }
}
