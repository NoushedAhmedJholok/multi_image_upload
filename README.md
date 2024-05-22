<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>



# Multi Image/File Upload In Laravel 
## Hope this code or this solution will help you a lot. 
### Let's be friends ? [LinkedIn](https://www.linkedin.com/in/noushedahmedjholok) [Facebook](https://www.facebook.com/NoushedAhmedJholok)

## Here I will show how to upload multi image/file. And it will be saved in local file image and database with this name.


## First you need to create a Laravel project.

### This is form (Blade or HTML).
```language
 
<form action="{{route('insert')}}" method="post" enctype="multipart/form-data">
    @csrf
    <label for="product" style="font-size:24px">Products Name</label>
    <input type="text" name="products_name" id="product" placeholder="Here Enter Product Name">
          <label class="upload__btn">
            <p>Upload images</p>
            <input type="file" name="images[]" multiple data-max_length="20" class="upload__inputfile">
          </label>
   <p><button type="submit" style="background: transparent; border: none;color:#000;">Upload Submit</button></p>
</form>
```
### This is Controller ( Code ).
```language
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
```
### Route web.php
```language
Route::post('/home/insert', [App\Http\Controllers\HomeController::class, 'insert'])->name('insert');
```

### You need to create 2 models. All the details will be inserted in one of them. Another image will be inserted.

### If went to show data ( i only show last 'id' data ) 
```language
                <h3 class="text-info bg-dark p-1">Show Only Last Product Name</h3>
            </div>
            @foreach ($products as $item)
                <h3>{{$item->product_name}}</h3>
            @endforeach
        </div>

            <div class="show_img">
                <h3 class="text-info bg-dark p-1">Show Only Last Product Images</h3>
            </div>
            
            @foreach ($images as $item)
                <img width="50px" src="{{asset('uploads/images/')}}/{{$item->images}}" alt="hgh">
            @endforeach
```
### Sent from the controller like this ( show data ) 
```language
        $products_idd = Product::latest()->value('id');
        $products = Product::orderBy('id', 'desc')->latest()->limit(1)->get();
        $images = Images::where('product_id' , $products_idd)->get();
        return view('home', compact('products', 'images'));
```

### two Models Name And Table 

```language
Model Name is Images 
Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->String('product_id');
            $table->String('images');
            $table->timestamps();
});

Model Name is Products
Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->String('product_name');
            $table->String('product_id');
            $table->timestamps();
});
```
## Hope this code or this solution will help you a lot. 
### Let's be friends ? [LinkedIn](https://www.linkedin.com/in/noushedahmedjholok) [Facebook](https://www.facebook.com/NoushedAhmedJholok)






