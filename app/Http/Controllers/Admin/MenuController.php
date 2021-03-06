<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuStoreRequest;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::all();
        return view("admin.menus.index", compact("menus"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view("admin.menus.create", compact("categories"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuStoreRequest $request)
    {
        $menu = Menu::create([
            "name" => $request->name,
            "price" => $request->price,
            "description" => $request->description,
        ]);

        if ($request->has("categories")) {
            $menu->categories()->attach($request->categories);
        }

        return to_route("admin.menus.index");
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        $categories = Category::all();
        return view("admin.menus.edit", compact("menu", "categories"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            "name" => "required",
            "price" => "required",
            "description" => "required"
        ]);

        $menu->update([
            "name" => $request->name,
            "price" => $request->price,
            "description" => $request->description,
        ]);

        if ($request->has("categories")) {
            $menu->categories()->sync($request->categories);
        }

        return to_route("admin.menus.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        $menu->categories()->detach();
        return to_route("admin.menus.index");
    }
}
