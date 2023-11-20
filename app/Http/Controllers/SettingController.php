<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;


use Illuminate\Http\Request;
use App\Models\Setting; // Import your Site model

class SettingController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'sub_title' => 'required|string',
            'twitter_link' => 'required|string',
            'instagram_link' => 'required|string',
            'visa_link' => 'required|string',
            'mastercard_link' => 'required|string',
            'site_logo' => 'required|image',
            'background_banner' => 'required|image',
        ]);

        // Upload and store the images in the public folder
        $siteLogoPath = $request->file('site_logo')->store('public/images');
        $backgroundBannerPath = $request->file('background_banner')->store('public/images');

        // Generate URLs for the images using asset()
        $siteLogoUrl = asset(str_replace('public', 'storage', $siteLogoPath));
        $backgroundBannerUrl = asset(str_replace('public', 'storage', $backgroundBannerPath));

        // Create a new site record with image paths
        $site = Setting::create([
            'title' => $data['title'],
            'sub_title' => $data['sub_title'],
            'twitter_link' => $data['twitter_link'],
            'instagram_link' => $data['instagram_link'],
            'visa_link' => $data['visa_link'],
            'mastercard_link' => $data['mastercard_link'],
            'site_logo' => $siteLogoUrl,
            'background_banner' => $backgroundBannerUrl,
        ]);

        return response()->json(['message' => 'Site created successfully', 'site' => $site]);
    }


    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'sub_title' => 'required|string',
            'twitter_link' => 'required|string',
            'instagram_link' => 'required|string',
            'visa_link' => 'required|string',
            'mastercard_link' => 'required|string',
        ]);

        $site = Setting::findOrFail($id);

        // Update site data
        $site->update($data);

        if ($request->hasFile('site_logo')) {
            // Delete the old image from the public folder
            Storage::delete($site->site_logo);

            // Upload and store the new image
            $siteLogoPath = $request->file('site_logo')->store('public/images');
            $site->site_logo = Storage::url($siteLogoPath);
        }

        if ($request->hasFile('background_banner')) {
            // Delete the old image from the public folder
            Storage::delete($site->background_banner);

            // Upload and store the new image
            $backgroundBannerPath = $request->file('background_banner')->store('public/images');
            $site->background_banner = Storage::url($backgroundBannerPath);
        }

        $site->save();

        return response()->json(['message' => 'Site updated successfully', 'site' => $site]);
    }

    // Delete a site
    public function destroy($id)
    {
        $site = Setting::findOrFail($id);
        $site->delete();

        return response()->json(['message' => 'Site deleted successfully']);
    }

    // Get a list of all sites
    public function index()
    {
        $sites = Setting::all();

        return response()->json(['sites' => $sites]);
    }

    // Get details of a specific site
    public function show($id)
    {
        $site = Setting::findOrFail($id);

        return response()->json(['site' => $site]);
    }
}
