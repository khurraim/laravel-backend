<?php

namespace App\Http\Controllers;

use App\Models\SocialIcon;
use Illuminate\Http\Request;

class SocialIconsController extends Controller
{
    public function index()
    {
        $socialIcons = SocialIcon::all();
        return response()->json($socialIcons);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'link' => 'required',
        ]);

        $socialIcon = SocialIcon::create([
            'name' => $request->name,
            'link' => $request->link,
        ]);

        return response()->json($socialIcon);
    }

    public function show($id)
    {
        $socialIcon = SocialIcon::findOrFail($id);
        return response()->json($socialIcon);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'link' => 'required',
        ]);

        $socialIcon = SocialIcon::findOrFail($id);
        $socialIcon->update([
            'name' => $request->name,
            'link' => $request->link,
        ]);

        return response()->json($socialIcon);
    }

    public function destroy($id)
    {
        $socialIcon = SocialIcon::findOrFail($id);
        $socialIcon->delete();

        return response()->json(['message' => 'Social Icon deleted']);
    }
}
