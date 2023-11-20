<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormGroup;

class FormGroupController extends Controller
{
    //
    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'title' => 'required|string',
    //         'subtitle' => 'required|string',
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust file validation rules as needed
    //     ]);

    //     // Handle image upload and storage here, then save the path or URL to the database
    //     // Handle featured image upload and storage here
    //     if ($request->hasFile('image')) {
    //         $featuredImageFile = $request->file('image');

    //         // Check if the uploaded file is valid
    //         if ($featuredImageFile->isValid()) {
    //             $featuredImagePath = $featuredImageFile->store('uploads', 'public');
    //             $formGroup->image = $featuredImagePath;
    //         } else {
    //             // Handle invalid file
    //             throw new \Exception('Invalid featured image file');
    //         }
    //     }

    //     $formGroup = FormGroup::create($data);

    //     return response()->json($formGroup, 201);
    // }

    public function index()
    {
        // Retrieve all form groups
        $formGroups = FormGroup::all();

        // Return a JSON response with the form groups
        return response()->json($formGroups);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Adjust file validation rules as needed
        ]);

        // Create a new FormGroup instance
        $formGroup = new FormGroup();

        // Set the properties of the form group
        $formGroup->title = $data['title'];
        $formGroup->subtitle = $data['subtitle'];

        // Handle featured image upload and storage here
        if ($request->hasFile('image')) {
            $featuredImageFile = $request->file('image');

            // Check if the uploaded file is valid
            if ($featuredImageFile->isValid()) {
                $featuredImagePath = $featuredImageFile->store('uploads', 'public');
                $formGroup->image = $featuredImagePath;
            } else {
                // Handle invalid file
                throw new \Exception('Invalid featured image file');
            }
        }

        // Save the form group to the database
        $formGroup->save();

        return response()->json($formGroup, 201);
    }

    public function show($id)
    {
        // Retrieve a specific form group by ID
        $formGroup = FormGroup::find($id);

        if (!$formGroup) {
            return response()->json(['message' => 'Form group not found'], 404);
        }

        return response()->json($formGroup);
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $data = $request->validate([
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Adjust file validation rules as needed
        ]);

        // Find the form group by ID
        $formGroup = FormGroup::find($id);

        if (!$formGroup) {
            return response()->json(['message' => 'Form group not found'], 404);
        }

        // Update the form group properties
        $formGroup->title = $data['title'];
        $formGroup->subtitle = $data['subtitle'];

        if ($request->hasFile('image')) {
            $featuredImageFile = $request->file('image');

            if ($featuredImageFile->isValid()) {
                $featuredImagePath = $featuredImageFile->store('uploads', 'public');
                $formGroup->image = $featuredImagePath;
            } else {
                return response()->json(['message' => 'Invalid featured image file'], 400);
            }
        }

        $formGroup->save();

        return response()->json($formGroup);
    }


    public function destroy($id)
    {
        // Find the form group by ID and delete it
        $formGroup = FormGroup::find($id);

        if (!$formGroup) {
            return response()->json(['message' => 'Form group not found'], 404);
        }

        $formGroup->delete();

        return response()->json(['message' => 'Form group deleted'], 200);
    }


}
