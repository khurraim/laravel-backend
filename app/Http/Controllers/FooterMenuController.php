<?php

namespace App\Http\Controllers;
use App\Models\FooterMenu;

use Illuminate\Http\Request;

class FooterMenuController extends Controller
{
    //
    public function index()
    {
        $menus = FooterMenu::all();
        return response()->json($menus);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'page_id' => 'required'
        ]);

        $menu = FooterMenu::create($validatedData);
        return response()->json($menu, 201);
    }

    public function show($id)
    {
        $menu = FooterMenu::findOrFail($id);
        return response()->json($menu);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'page_id' => 'required'
        ]);

        $menu = FooterMenu::findOrFail($id);
        $menu->update($validatedData);
        return response()->json($menu, 200);
    }

    public function destroy($id)
    {
        $menu = FooterMenu::findOrFail($id);
        $menu->delete();
        return response()->json(null, 204);
    }
}
