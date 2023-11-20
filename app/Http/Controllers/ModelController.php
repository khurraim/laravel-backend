<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewModel;
use App\Models\Service;

use App\Models\Rate;
use App\Models\Gallery;
use App\Models\AddRate;

use Illuminate\Support\Facades\Log; // Import the Log facade

use Illuminate\Http\UploadedFile; // Import the UploadedFile class

use DB;

use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Storage;

use Illuminate\Http\File;





class ModelController extends Controller
{
    // Create a new model
    // public function store(Request $request)
    // {
    //     try {
    //         $data = $request->validate([
    //             'title' => 'required',
    //             'location' => 'required',
    //             'modelDescription' => 'required',
    //             'category_id' => 'required',
    //             'featuredImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules as needed
    //             'services' => 'required|array',
    //             'services.*.name' => 'required|string',
    //             'stats' => 'required|array',
    //             'stats.*.name' => 'required|string',
    //             'stats.*.value' => 'required|string',
    //             'video' => 'required|mimes:mp4,mov,ogg'
    //             //'gallery' => 'required|array',
    //             //'gallery.*.image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         ]);

    //         // Handle the featured image
    //         $featuredImage = $data['featuredImage'];
    //         $featuredImagePath = $featuredImage->store('uploads', 'public'); // Customize the storage path as needed

    //         // Video
    //         if ($request->hasFile('video')) {
    //             $video = $request->file('video');
    //             $videoName = time() . '.' . $video->getClientOriginalExtension();
    //             $videoPath = $video->store('uploads','public');
    //             //$videoPath = public_path('/uploads/' . $videoName);
    //             //$video->move(public_path('/uploads'), $videoName);
    //         }
            

    //         // Create the main model
    //         $model = NewModel::create([
    //             'title' => $data['title'],
    //             'location' => $data['location'],
    //             'modelDescription' => $data['modelDescription'],
    //             'category_id' => $data['category_id'],
    //             'featuredImage' => $featuredImagePath,
    //             'video' => $videoPath
    //         ]);

    //         // Create separate records for each service
    //         foreach ($data['services'] as $service) {
    //             Service::create([
    //                 'model_id' => $model->id,
    //                 'name' => $service['name'],
    //             ]);
    //         }

    //         // Create separate records for each stat
    //         foreach ($data['stats'] as $stat) {
    //             Stat::create([
    //                 'model_id' => $model->id,
    //                 'name' => $stat['name'],
    //                 'value' => $stat['value'],
    //             ]);
    //         }

            


    //         return response()->json(['message' => 'Model Created Successfully'], 201);
    //     } catch (\Exception $e) {
    //         // Return Error Response
    //         return response()->json(['error' => 'Internal Server Error: ' . $e->getMessage()], 500);
    //     }
    // }


    public function store(Request $request)
    {
        try {
            // Validate the request data
            $data = $request->validate([
                'title' => 'required',
                'location' => 'required',
                'modelDescription' => 'required',
                'services' => 'required|array',
                'services.*.name' => 'required|string',
                'subLocation' => 'required|string',
                'age' => 'required',
                'weight' => 'required|string',
                'nationality' => 'required|string',
                'dressSize' => 'required|string',
                'price' => 'required',
                'addRate' => 'required|array', // Add this rule for 'addRate'
                'addRate.*.duration' => 'required|string', // Validation for 'duration' within 'addRate'
                'addRate.*.incall' => 'required|string', // Validation for 'incall' within 'addRate'
                'addRate.*.outcall' => 'required|string', // Validation for 'outcall' within 'addRate'
            ]);

            // Handle the featured image
            if ($request->hasFile('featuredImage')) {
                $featuredImage = $request->file('featuredImage');
                $featuredImagePath = $featuredImage->store('uploads', 'public'); // Customize the storage path as needed
            } else {
                return response()->json(['error' => 'Featured image is required'], 400);
            }

            // Handle the video
            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $videoPath = $video->store('uploads', 'public'); // Customize the storage path as needed
            } else {
                return response()->json(['error' => 'Video is required'], 400);
            }

            // Create the main model
            $model = NewModel::create([
                'title' => $data['title'],
                'location' => $data['location'],
                'modelDescription' => $data['modelDescription'],
                'subLocation' => $data['subLocation'],
                'age' => $data['age'],
                'weight' => $data['weight'],
                'nationality' => $data['nationality'],
                'dressSize' => $data['dressSize'],
                'featuredImage' => $featuredImagePath,
                'video' => $videoPath,
                'price' => $data['price'],
                'created_by' => 'admin'
            ]);

            // Create separate records for each service
            foreach ($data['services'] as $service) {
                Service::create([
                    'model_id' => $model->id,
                    'name' => $service['name'],
                ]);
            }

            //return response()->json($data['addRate'],500);

            // Create separate record for each addRate
            foreach ($data['addRate'] as $addRate) {
                //return response()->json($addRate['duration'],500);
                    AddRate::create([
                        'model_id' => $model->id,
                        'duration' => $addRate['duration'],
                        'incall' => $addRate['incall'],
                        'outcall' => $addRate['outcall'],
                    ]);
                
            }


            // Handle the gallery images
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $imagePaths = [];
    
                foreach ($images as $image) {
                    $imageName = $image->getClientOriginalName(); // Get the original image name
                    $imagePath = $image->storeAs('images', $imageName, 'public'); // Store in the 'public/images' directory
                    $imagePaths[] = $imagePath;

                    // Save record in database
                    $gallery = new Gallery();
                    $gallery->model_id = $model->id; // Set the current model's ID
                    $gallery->image = $imagePath; // Save the image path
                    $gallery->save();

                }
            
            } 
            

            return response()->json(['message' => 'Model Created Successfully'], 201);
        } catch (\Exception $e) {
            // Return Error Response
            return response()->json(['error' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }


    // This function will be used
    // if contact form on user side is used
    public function StoreByUser(Request $request)
    {
        try {
            // Validate the request data
            $data = $request->validate([
                'title' => 'required',
                'location' => 'required',
                'modelDescription' => 'required',
                'services' => 'required|array',
                'services.*.name' => 'required|string',
                'subLocation' => 'required|string',
                'age' => 'required',
                'weight' => 'required|string',
                'nationality' => 'required|string',
                'dressSize' => 'required|string',
                'price' => 'required',
                'addRate' => 'required|array', // Add this rule for 'addRate'
                'addRate.*.duration' => 'required|string', // Validation for 'duration' within 'addRate'
                'addRate.*.incall' => 'required|string', // Validation for 'incall' within 'addRate'
                'addRate.*.outcall' => 'required|string', // Validation for 'outcall' within 'addRate'
            ]);

            // Handle the featured image
            if ($request->hasFile('featuredImage')) {
                $featuredImage = $request->file('featuredImage');
                $featuredImagePath = $featuredImage->store('uploads', 'public'); // Customize the storage path as needed
            } else {
                return response()->json(['error' => 'Featured image is required'], 400);
            }

            // Handle the video
            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $videoPath = $video->store('uploads', 'public'); // Customize the storage path as needed
            } else {
                return response()->json(['error' => 'Video is required'], 400);
            }

            // Create the main model
            $model = NewModel::create([
                'title' => $data['title'],
                'location' => $data['location'],
                'modelDescription' => $data['modelDescription'],
                'subLocation' => $data['subLocation'],
                'age' => $data['age'],
                'nationality' => $data['nationality'],
                'dressSize' => $data['dressSize'],
                'featuredImage' => $featuredImagePath,
                'video' => $videoPath,
                'price' => $data['price'],
                'weight' => $data['weight'],
                'created_by' => 'user'
            ]);

            // Create separate records for each service
            foreach ($data['services'] as $service) {
                Service::create([
                    'model_id' => $model->id,
                    'name' => $service['name'],
                ]);
            }

            //return response()->json($data['addRate'],500);

            // Create separate record for each addRate
            foreach ($data['addRate'] as $addRate) {
                //return response()->json($addRate['duration'],500);
                    AddRate::create([
                        'model_id' => $model->id,
                        'duration' => $addRate['duration'],
                        'incall' => $addRate['incall'],
                        'outcall' => $addRate['outcall'],
                    ]);
                
            }


            // Handle the gallery images
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $imagePaths = [];
    
                foreach ($images as $image) {
                    $imageName = $image->getClientOriginalName(); // Get the original image name
                    $imagePath = $image->storeAs('images', $imageName, 'public'); // Store in the 'public/images' directory
                    $imagePaths[] = $imagePath;

                    // Save record in database
                    $gallery = new Gallery();
                    $gallery->model_id = $model->id; // Set the current model's ID
                    $gallery->image = $imagePath; // Save the image path
                    $gallery->save();

                }
            
            } 
            

            return response()->json(['message' => 'Model Created Successfully'], 201);
        } catch (\Exception $e) {
            // Return Error Response
            return response()->json(['error' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }


    // public function index(Request $request)
    // {
    //     $requestedAges = $request->input('age');
    //     $requestedNationality = $request->input('nationality');
    //     $requestedPrice = $request->input('price');
    //     $requestedLocation = $request->input('location');
    //     $requestedDressSize = $request->input('dressSize');

    //     // Start with all models
    //     $query = NewModel::query(); 
        

    //     if (!empty($requestedAges) && strpos($requestedAges, 'all') === false) {
    //         // Convert comma-separated string to an array
    //         $agesArray = explode(',', $requestedAges);
    //         $query->whereIn('age', $agesArray);
    //     }
        

    //     if (!empty($requestedNationality) && strpos($requestedNationality, 'all') === false) {
    //         // Convert comma-separated string to an array
    //         $NationalityArray = explode(',', $requestedNationality);
    //         $query->whereIn('nationality', $NationalityArray);
    //     }

    //     // Filter by price if it's not 'all'
    //     if ($requestedPrice !== 'all') {
    //         $query->where('price', $requestedPrice);
    //     }

    //     if ($requestedLocation !== 'all') {
    //         $query->where('location', $requestedLocation);
    //     }

    //     if ($requestedDressSize !== 'all') {
    //         $query->where('dressSize', $requestedDressSize);
    //     }

    //     // Get the filtered models
    //     $models = $query->get();

    //     return response()->json($models, 200);
    // }

    public function index(Request $request)
{
    $requestedAges = $request->input('age');
    $requestedNationality = $request->input('nationality');
    $requestedPrice = $request->input('price');
    $requestedLocation = $request->input('location');
    $requestedDressSize = $request->input('dressSize');

    // Start with all models
    $query = NewModel::query();

    if (!empty($requestedAges) && strpos($requestedAges, 'all') === false) {
        // Convert comma-separated string to an array
        $agesArray = explode(',', $requestedAges);
        $query->whereIn('age', $agesArray);
    }

    if (!empty($requestedNationality) && strpos($requestedNationality, 'all') === false) {
        // Convert comma-separated string to an array
        $nationalitiesArray = explode(',', $requestedNationality);
        
        // Use nested orWhere clauses for nationality
        $query->where(function ($query) use ($nationalitiesArray) {
            foreach ($nationalitiesArray as $nationality) {
                $query->orWhere('nationality', $nationality);
            }
        });
    }

    if (!empty($requestedPrice) && strpos($requestedPrice, 'all') === false) {
        // Convert comma-separated string to an array
        $pricesArray = explode(',', $requestedPrice);
        
        // Use nested orWhere clauses for nationality
        $query->where(function ($query) use ($pricesArray) {
            foreach ($pricesArray as $price) {
                $query->orWhere('price', $price);
            }
        });
    }

    if (!empty($requestedLocation) && strpos($requestedLocation, 'all') === false) {
        // Convert comma-separated string to an array
        $locationArray = explode(',', $requestedLocation);
        
        // Use nested orWhere clauses for nationality
        $query->where(function ($query) use ($locationArray) {
            foreach ($locationArray as $location) {
                $query->orWhere('location', $location);
            }
        });
    }

    

    // Filter by price if it's not 'all'
    // if ($requestedPrice !== 'all') {
    //     $query->where('price', $requestedPrice);
    // }

    // if ($requestedLocation !== 'all') {
    //     $query->where('location', $requestedLocation);
    // }

    if ($requestedDressSize !== 'all') {
        $query->where('dressSize', $requestedDressSize);
    }

    // Get the filtered models
    $models = $query->get();

    return response()->json($models, 200);
}




    public function IndexByUser(Request $request)
    {
        // Start with all models
        $query = NewModel::query();


        $query->where('created_by','user');
        // Get the filtered models
        $models = $query->get();

        return response()->json($models, 200);
    }

    public function IndexByAdmin(Request $request)
    {
        // Start with all models
        $query = NewModel::query();


        $query->where('created_by','admin');
        // Get the filtered models
        $models = $query->get();

        return response()->json($models, 200);
    }

    public function AllModels()
    {
        $models = NewModel::all();
        return response()->json($models, 200);
    }


    // Render unique ages 
    // Read all unique age values
    public function uniqueAges()
    {
        $ages = NewModel::distinct()->pluck('age');

        return response()->json($ages, 200);
    }

    // Read unique nationalities with the count of records for each nationality
    public function uniqueNationalities()
    {
        $nationalitiesWithCount = NewModel::select('nationality', DB::raw('count(*) as count'))
            ->groupBy('nationality')
            ->get();

        return response()->json($nationalitiesWithCount, 200);
    }

    public function uniquePrices()
    {
        $PricesWithCount = NewModel::select('price', DB::raw('count(*) as count'))
        ->groupBy('price')
        ->get();

        return response()->json($PricesWithCount, 200);
    }

    public function uniqueLocations()
    {
        $LocationsWithCount = NewModel::select('location', DB::raw('count(*) as count'))
        ->groupBy('location')
        ->get();

        return response()->json($LocationsWithCount, 200);
    }

    public function uniqueDressSizes()
    {
        $DressSizeWithCount = NewModel::select('dressSize', DB::raw('count(*) as count'))
        ->groupBy('dressSize')
        ->get();

        return response()->json($DressSizeWithCount, 200);
    }

    public function uniqueRates($id)
    {
        $rates = AddRate::where('model_id',$id)->get();

        return response()->json(['rates'=>$rates],200);
    }

    // Read a specific model by ID
    public function show($id)
    {
        $model = NewModel::find($id);

        if (!$model) {
            return response()->json(['message' => 'Model not found'], 404);
        }

        return response()->json($model, 200);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'location' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'subLocation' => 'required',
            'nationality' => 'required',
            'weight' => 'required',
            'age' => 'required',
            'dressSize' => 'required',
            'addRate' => 'required|array', // Add this rule for 'addRate'
            'addRate.*.duration' => 'required|string', // Validation for 'duration' within 'addRate'
            'addRate.*.incall' => 'required|string', // Validation for 'incall' within 'addRate'
            'addRate.*.outcall' => 'required|string',
            //'featuredImage' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Example image validation
        ]);

        try {
            $model = NewModel::findOrFail($id);

            // Update model fields
            $model->update([
                'title' => $request->title,
                'modelDescription' => $request->description,
                'location' => $request->location,
                'price' => $request->price,
                'age' => $request->age,
                'subLocation' => $request->subLocation,
                'nationality' => $request->nationality,
                'dressSize' => $request->dressSize,
                'weight' => $request->weight
            ]);

            // Handle image upload and update
            if ($request->hasFile('featuredImage')) {
                // Delete the old image file if it exists
                if ($model->featuredImage) {
                    \Storage::disk('public')->delete($model->featuredImage);
                }

                $imagePath = $request->file('featuredImage')->store('uploads', 'public');
                $model->featuredImage = $imagePath;
                $model->save();
            }

            // Handle the video
             // Handle the video file
        if ($request->hasFile('video')) {
            $video = $request->file('video');

            // Delete the old video file if it exists
            if ($model->video) {
                \Storage::disk('public')->delete($model->video);
            }

            // Store the new video file
            $videoPath = $video->store('videos', 'public');
            $model->video = $videoPath;
            $model->save();
        }

            $servicesData = $request->input('services');

            if($servicesData) {
                foreach ($servicesData as $service) {
                    $id = $service['id'];
                    $name = $service['name'];
                
                    // Check if the service has an ID or if the ID is empty
                    if ($id === null || $id === 'undefined') {
                        // Create a new service without specifying an ID
                        $newService = Service::create(['name' => $name, 'model_id' => $model->id]);
                        
                    } else {
                        // Update the service with an existing ID
                        $existingService = Service::find($id);
                        if ($existingService) {
                            $existingService->update(['name' => $name]);
                        }
                    }
                }
            }

            $ratesData = $request->input('addRate');
            if($ratesData) {
                foreach($ratesData as $rate) {
                    $id = $rate['id'];
                    $duration = $rate['duration'];
                    $incall = $rate['incall'];
                    $outcall = $rate['outcall'];
                
                    // Check if the service has an ID or if the ID is empty
                    if ($id === null || $id === 'undefined') {
                        // Create a new service without specifying an ID
                        $newrate = AddRate::create([
                            'duration' => $duration, 
                            'incall' => $incall,
                            'outcall' => $outcall,
                            'model_id' => $model->id
                        ]);
                        
                    } else {
                        // Update the service with an existing ID
                        $existingRate = AddRate::find($id);
                        if ($existingRate) {
                            $existingRate->update([
                                'duration' => $duration, 
                                'incall' => $incall,
                                'outcall' => $outcall,
                            ]);
                        }
                    }
                }
            }

            // Handle the gallery images
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $imagePaths = [];
    
                foreach ($images as $image) {
                    $imageName = $image->getClientOriginalName(); // Get the original image name
                    $imagePath = $image->storeAs('images', $imageName, 'public'); // Store in the 'public/images' directory
                    $imagePaths[] = $imagePath;

                    // Save record in database
                    $gallery = new Gallery();
                    $gallery->model_id = $model->id; // Set the current model's ID
                    $gallery->image = $imagePath; // Save the image path
                    $gallery->save();

                }
            
            } 
            

            return response()->json(['message' => 'Model updated successfully']);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error($e);

            // Return an error response
            return response()->json(['error' => 'Internal Server Error.'.$e->getMessage()], 500);
        }
    }

    


    // Delete a model by ID
    public function destroy($id)
    {
        $model = NewModel::find($id);

        if (!$model) {
            return response()->json(['message' => 'Model not found'], 404);
        }

        try {
            // Delete related services first
            $model->services()->delete();

            // Delete related rates first
            $model->rates()->delete();

            // Delete Related Gallery First
            $model->gallery()->delete();

            // Now, delete the model
            $model->delete();

            return response()->json(['message' => 'Model deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Error deleting model: ' . $e->getMessage());
            return response()->json(['message' => 'The Error is : '. $e->getMessage()], 500);
        }
    }

    public function ShowServices($id)
    {
        try {
            // Retrieve services by model_id
            $services = Service::where('model_id', $id)->get();

            return response()->json(['services' => $services], 200);
        } catch (\Exception $e) {
            // Handle any errors and return an error response
            return response()->json(['message' => 'Error retrieving services', 'error' => $e->getMessage()], 500);
        }
    }

    public function ShowStats($id)
    {
        try
        {
            $stats = Stat::where('model_id',$id)->get();
            return response()->json(['stats' => $stats], 200);
        }
        catch(\Exception $e)
        {
            return response()->json(['message' => 'Error retrieving services', 'error' => $e->getMessage()], 500);
        }
    }

    public function ShowGallery($id)
    {
        try
        {
            $gallery = Gallery::where('model_id',$id)->get();
            return response()->json($gallery, 200, );
        }
        catch(\Exception $e)
        {
            return response()->json(['message'=>'Error receiving gallery','error'=>$e.getMessage()], 500);
        }
    }

    public function destroyService($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Model not found'], 404);
        }

        try {
            
            $service->delete();

            return response()->json(['message' => 'Service deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Error deleting model: ' . $e->getMessage());
            return response()->json(['message' => 'The Error is : '. $e->getMessage()], 500);
        }   
    }

    public function destroyRate($id)
    {
        $rate = AddRate::find($id);

        if (!$rate) {
            return response()->json(['message' => 'Rate not found'], 404);
        }

        try {
            
            $rate->delete();

            return response()->json(['message' => 'Rate deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Error deleting model: ' . $e->getMessage());
            return response()->json(['message' => 'The Error is : '. $e->getMessage()], 500);
        }   
    }


    public function destroyGallery($id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json(['message' => 'Gallery not found'], 404);
        }

        try {
            
            $gallery->delete();

            return response()->json(['message' => 'Gallery deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Error deleting gallery: ' . $e->getMessage());
            return response()->json(['message' => 'The Error is : '. $e->getMessage()], 500);
        }   
    }

    


}
