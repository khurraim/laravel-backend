<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Http\Response;

class PagesController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        return response()->json($pages, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|unique:pages',
            'description' => 'required|string',
        ]);

        // $existingPage = Page::where('title', $title)->first();
        // if ($existingPage) {
        //     return response()->json(['message' => 'Page title already exists'], 409);
        // }

        // Replace <br> with line breaks
        $description = str_replace('<br>', "<br/>", $request->input('description'));

        $page = new Page();
        $page->title = $request->input('title');
        $page->description = $description;
        $page->save();

        return response()->json(['message' => 'Page created successfully']);
    }


    public function show($id)
    {
        $page = Page::findOrFail($id);
        //return response()->json(['page' => $page]);
        return response()->json($page,200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|unique:pages',
            'description' => 'required|string',
        ]);

        $page = Page::findOrFail($id);
        $page->title = $request->input('title');
        $description = str_replace('<br>', "<br/>", $request->input('description'));
        $page->description = $description;
        $page->save();

        return response()->json(['message' => 'Page updated successfully']);
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return response()->json(['message' => 'Page deleted successfully']);
    }

    public function UniquePage($title)
    {
        $existingPage = Page::where('title', $title)->first();
        if ($existingPage) {
            return response()->json(['message' => 'Page title already exists'], 409);
        }

        return response()->json(['message' => 'Page title is unique'],200);
    }

}
