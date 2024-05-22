@extends('layouts.app')

@section('content')
<form action="{{route('insert')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="upload__box">
        <div class="upload__btn-box">
            <label for="product" style="font-size:24px">Products Name</label>
            <input class="form-control mb-3 w-50" type="text" name="products_name" id="product" placeholder="Here Enter Product Name">
          <label class="upload__btn">
            <p>Upload images</p>
            <input type="file" name="images[]" multiple data-max_length="20" class="upload__inputfile">
          </label>
          <br> <br>
          <label class="upload__btn">
            <p><button type="submit" style="background: transparent; border: none;color:#000;">Upload Submit</button></p>
          </label>
        </div>
        <div class="upload__img-wrap"></div>
      </div>
</form>
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div class="show_img">
                <h3 class="text-info bg-dark p-1">Show Only Last Product Name</h3>
            </div>
            @foreach ($products as $item)
                <h3>{{$item->product_name}}</h3>
            @endforeach
        </div>
        <div class="col-lg-6">
            <div class="show_img">
                <h3 class="text-info bg-dark p-1">Show Only Last Product Images</h3>
            </div>
            
            @foreach ($images as $item)
                <img width="50px" src="{{asset('uploads/images/')}}/{{$item->images}}" alt="hgh">
            @endforeach
        </div>
    </div>
</div>
@endsection
