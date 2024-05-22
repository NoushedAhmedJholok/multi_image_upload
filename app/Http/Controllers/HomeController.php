<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products_idd = Product::latest()->value('id');
        $products = Product::orderBy('id', 'desc')->latest()->limit(1)->get();
        $images = Images::where('product_id' , $products_idd)->get();
        return view('home', compact('products', 'images'));
    }
// Multi Images Upload Controller 
public function insert(Request $request)
{
    // Validate the request
    $request->validate([
        'products_name' => 'required|string|max:255',
        'images' => 'required|array',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
    ]);

    // Insert the product and get its ID
    $getinsertId = Product::insertGetId([
        'product_name' => $request->products_name,
        'product_id' => 10, // Replace with actual logic for product_id if needed
    ]);

    // Ensure the directory exists
    $uploadPath = public_path('uploads/images');
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    // Loop through each uploaded image
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $index => $image) {
            // Create a unique name for the image
            $imageName = $getinsertId . '_' . $index . '.' . $image->getClientOriginalExtension();
            
            // Move the image to the public/uploads/images directory
            $image->move($uploadPath, $imageName);

            // Insert the image information into the Images table
            Images::insert([
                'product_id' => $getinsertId,
                'images' => $imageName,
            ]);
        }
    }

    return back()->with('success', 'Product and images uploaded successfully.');
}

}
