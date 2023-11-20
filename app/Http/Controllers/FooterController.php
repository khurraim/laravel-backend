<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Footer;
use Illuminate\Http\Response;

class FooterController extends Controller
{
    //
    public function index()
    {
        $footer = Footer::all();
        return response()->json($footer);
    }

    // public function store(Request $request)
    // {
    //     try
    //     {
    //         $footer = new Footer;
    //         $footer->copyright_text = $request->copyright_text;

    //         // Handle featured image upload and storage here
    //         if ($request->hasFile('footer_image')) {
    //             $featuredImageFile = $request->file('footer_image');

    //             // Check if the uploaded file is valid
    //             if ($featuredImageFile->isValid()) {
    //                 $featuredImagePath = $featuredImageFile->store('uploads', 'public');
    //                 $footer->footer_image = $featuredImagePath;

    //             } else {
    //                 // Handle invalid file
    //                 throw new \Exception('Invalid featured image file');
    //             }
    //         }

    //         $footer->save();

    //         return response->json('Footer Content Added Successfully');

    //     }
    //     catch(\Exception $e)
    //     {
    //         // Return an error response
    //         return response()->json(['error' => 'Internal Server Error'], 500);
    //     }

    // }

    public function store(Request $request)
    {
        try {
            $footer = new Footer;
            $footer->copyright_text = $request->copyright_text;

            // Handle featured image upload and storage here
            if ($request->hasFile('footer_image')) {
                $featuredImageFile = $request->file('footer_image');

                // Check if the uploaded file is valid
                if ($featuredImageFile->isValid()) {
                    $featuredImagePath = $featuredImageFile->store('uploads', 'public');
                    $footer->footer_image = $featuredImagePath;
                } else {
                    // Handle invalid file
                    throw new \Exception('Invalid featured image file');
                }
            }

            $footer->save();

            return response()->json('Footer Content Added Successfully');

        } catch (\Exception $e) {
            // Return an error response
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    public function show($id)
    {
        $footer = Footer::findOrFail($id);
        return response()->json($footer);
    }

    public function update(Request $request, $id)
    {
        
        $request->validate([
            'copyright_text' => 'string|required'
        ]);

        $footer = Footer::findOrFail($id);
        if (!$footer) {
                return response()->json(['message' => 'Content not found'], 404);
        }

        $footer->copyright_text = $request->input('copyright_text');

            // Handle featured image upload and storage if a new image is provided
        if ($request->hasFile('footer_image')) {
        
            $featuredImageFile = $request->file('footer_image');

            // Check if the uploaded file is valid
            if ($featuredImageFile->isValid()) {
                $featuredImagePath = $featuredImageFile->store('uploads', 'public');
                $footer->footer_image = $featuredImagePath;
            } else {
                // Handle invalid file
                throw new \Exception('Invalid featured image file');
            }

        }

        $footer->save();

        return response()->json('Footer Content Updated Successfully');
        
    }

    public function destroy($id)
    {
        
    }
}
