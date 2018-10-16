<?php

namespace App\Http\Controllers\Site;

use App\Site\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\Site\{
    MenuResource,
    MenuCollection
};

class MenuController extends Controller
{
    protected $menu;

    /**
    * Contructor
    * Ensure Menu model is instanciated 
    */
    function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listFull()
    {
        return new MenuCollection( $this->menu->fullList() );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listSelect()
    {
        return new MenuCollection( $this->menu->selectList() );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listMenu()
    {
        return response()->json( $this->menu->menuList() );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json( $this->menu->createData() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return new MenuResource( $this->menu->store($request) );
    }

    /**
     * Display the specified resource.
     *
     * @param  mised $identifier
     * @return \Illuminate\Http\Response
     */
    public function show($identifier)
    {
        return new MenuResource( $this->menu->show($identifier) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  mised $identifier
     * @return \Illuminate\Http\Response
     */
    public function edit($identifier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mised $identifier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $identifier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  mised $identifier
     * @return \Illuminate\Http\Response
     */
    public function destroy($identifier)
    {
        //
    }
}
