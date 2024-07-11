@extends('backend.layouts.master')

@section('main-content')
<style>
 #holder img {
            max-height: 100px;
            margin: 5px;
        }
        #galleryHolder img{
          max-height: 100px;
          margin: 2px;
        }
</style>
<div class="card">
    <h5 class="card-header">Add Product</h5>
    <div class="card-body">
      <form method="post" action="{{route('product.store')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-group">
          <label for="category_id">Category <span class="text-danger">*</span></label>
          <select name="category_id" id="category_id" class="form-control" required>
              <option value="">--Select any category--</option>
              @foreach($categories as $key=>$cat_data)
                  <option value='{{$cat_data->id}}'>{{$cat_data->title}}</option>
              @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Product Name <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="prod_name" placeholder="Enter Product Name"  value="{{old('prod_name')}}" class="form-control" required>
          @error('prod_name')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        
        <div class="form-group">
          <label for="description" class="col-form-label">Description</label>
          <textarea class="form-control" id="description" name="description" required>{{old('description')}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>


        <div class="form-group">
          <label for="short_desc" class="col-form-label">Short Description<span class="text-danger">*</span></label>
          <textarea class="form-control" id="short_desc" name="short_desc" required>{{old('short_desc')}}</textarea>
          @error('short_desc')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>



        {{-- <div class="form-group">
          <label for="is_featured">Is Featured</label><br>
          <input type="checkbox" name='is_featured' id='is_featured' value='1' checked> Yes                        
        </div> --}}
              
        {{-- {{$categories}} --}}
        

        {{-- <div class="form-group d-none" id="child_cat_div">
          <label for="child_cat_id">Sub Category</label>
          <select name="child_cat_id" id="child_cat_id" class="form-control">
              <option value="">--Select any category--</option>
              @foreach($parent_cats as $key=>$parent_cat)
                  <option value='{{$parent_cat->id}}'>{{$parent_cat->title}}</option>
              @endforeach
          </select>
        </div> --}}

        <div class="form-group">
          <label for="price" class="col-form-label">Regular Price($) <span class="text-danger">*</span></label>
          <input id="price" type="number" name="price" placeholder="Enter price" min="0"  value="{{old('price')}}" class="form-control" required>
          @error('price')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="sale_price" class="col-form-label">Sale Price($)</label>
          <input id="sale_price" type="number" name="sale_price" min="0" placeholder="Enter Sale Price"  value="{{old('sale_price')}}" class="form-control" required>
          @error('sale_price')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

      {{-- SKU --}}

        <div class="form-group">
          <label for="sku" class="col-form-label">SKU <span class="text-danger">*</span></label>
          <input id="sku" type="text" name="sku" placeholder="SKU"  value="{{old('sku')}}" class="form-control" required>
          @error('sku')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        {{-- Stock Status --}}
        <label class="col-form-label">Stock Status <span class="text-danger">*</span></label>
        <div class="container mt-4">
          <div class="form-group">
              <div class="form-check">
                  <input class="form-check-input" type="radio" name="productStatus" id="inStock" value="1" onclick="toggleQuantityField()">
                  <label class="form-check-label" for="inStock">
                      In Stock
                  </label>
              </div>
              <div class="form-check">
                  <input class="form-check-input" type="radio" name="productStatus" id="outOfStock" value="0" onclick="toggleQuantityField()">
                  <label class="form-check-label" for="outOfStock">
                      Out of Stock
                  </label>
              </div>
              <div class="form-check">
                  <input class="form-check-input" type="radio" name="productStatus" id="onBackOrder" value="2" onclick="toggleQuantityField()">
                  <label class="form-check-label" for="onBackOrder">
                      On Backorder
                  </label>
              </div>
          </div>
          <div class="form-group" id="quantityField" style="display: none;">
              <label for="quantity" class="col-form-label">Quantity</label>
              <input type="number" class="form-control" name="quantity" id="quantity" min="1">
          </div>
      </div>

      
      <div class="form-group">
        <label for="IGI_certificate" class="col-form-label">IGI Certificate link<span class="text-danger">*</span></label>
        <input id="IGI_certificate" type="text" name="IGI_certificate" placeholder="IGI Certificate Link"  value="{{old('IGI_cert')}}" class="form-control" required>
        @error('sku')
        <span class="text-danger">{{$message}}</span>
        @enderror
      </div>

      <div class="form-group">
        <label for="IGI_certificate" class="col-form-label">Document Number<span class="text-danger">*</span></label>
        <input id="document_number" type="text" name="document_number" placeholder="document_number"  value="{{old('document_number')}}" class="form-control" required>
        @error('document_number')
        <span class="text-danger">{{$message}}</span>
        @enderror
      </div>


      {{-- Attribute --}}
    
      @php
      $attributes = [
          'Type' => 'Lab Grown Diamond',
          'Shape' => 'Round Brilliant',
          'Carat Weight' => '0.38 ct',
          'Cut' => 'Ideal',
          'Colour' => 'E',
          'Clarity' => 'VS1',
          'Fluorescence' => 'None',
          'Availability' => 'Online Only',
          'Growth Method' => 'CVD',
          'Polish' => 'Excellent',
          'Symmetry' => 'Excellent',
          'Table' => '56.5%',
          'Depth' => '62.5%',
          'Ratio' => '1.01'
      ];
  @endphp

<hr/>
<h5> Attributes </h3>
<div class="row">
  @foreach ($attributes as $attribute => $value)
      <div class="col-md-4">
          <div class="form-group">
              <label for="{{ str_replace(' ', '_', strtolower($attribute)) }}">{{ $attribute }}</label>
              <input type="text" class="form-control" name="attributes[{{ $attribute }}]" id="{{ str_replace(' ', '_', strtolower($attribute)) }}" value="{{ old('attributes.' . $attribute, $value) }}" required>
          </div>
      </div>
      
      @if (($loop->iteration % 3) == 0 && !$loop->last)
          </div><div class="row">
      @endif
  @endforeach
</div>





        {{-- <div class="form-group">
          <label for="size">Size</label>
          <select name="size[]" class="form-control selectpicker"  multiple data-live-search="true">
              <option value="">--Select any size--</option>
              <option value="S">Small (S)</option>
              <option value="M">Medium (M)</option>
              <option value="L">Large (L)</option>
              <option value="XL">Extra Large (XL)</option>
              <option value="2XL">Double Extra Large (2XL)</option>
              <option value="7US">7 US</option>
              <option value="8US">8 US</option>
              <option value="9US">9 US</option>
              <option value="10US">10 US</option>
              <option value="11US">11 US</option>
              <option value="12US">12 US</option>
              <option value="13US">13 US</option>
          </select>
        </div> --}}

        {{-- <div class="form-group">
          <label for="brand_id">Brand</label>
          <select name="brand_id" class="form-control">
              <option value="">--Select Brand--</option>
             @foreach($brands as $brand)
              <option value="{{$brand->id}}">{{$brand->title}}</option>
             @endforeach
          </select>
        </div> --}}

        {{-- <div class="form-group">
          <label for="condition">Condition</label>
          <select name="condition" class="form-control">
              <option value="">--Select Condition--</option>
              <option value="default">Default</option>
              <option value="new">New</option>
              <option value="hot">Hot</option>
          </select>
        </div> --}}

        {{-- <div class="form-group">
          <label for="stock">Quantity <span class="text-danger">*</span></label>
          <input id="quantity" type="number" name="stock" min="0" placeholder="Enter quantity"  value="{{old('stock')}}" class="form-control">
          @error('stock')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div> --}}

        {{-- <div class="form-group">
          <label for="mainphoto" class="col-form-label">Main Photo<span class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-secondary text-white">
                  <i class="fa fa-picture-o"></i> Choose
                  </a>
              </span>
          <input id="thumbnail" class="form-control" type="text" name="photo" value="{{old('photo')}}">
        </div>
        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
          @error('photo')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div> --}}

 <!-- Main Photo Input -->
 <div class="form-group">
  <label for="mainphoto" class="col-form-label">Main Photo<span class="text-danger">*</span></label>
  <div class="input-group">
      <span class="input-group-btn">
          <button class="btn btn-secondary text-white" type="button" onclick="document.getElementById('imageInput').click();"> 
              <i class="fa fa-picture-o"></i> Choose
          </button>
      </span>
      <input id="thumbnail" class="form-control" type="text" readonly>
  </div>
  <input type="file" id="imageInput" accept="image/*" style="display: none;" name="photo" onchange="previewImage(event)">
  <div id="holder" style="margin-top: 15px; max-height: 100px;"></div>
  <span id="error" class="text-danger"></span>
</div>

<!-- Image Gallery Input -->
<div class="form-group">
  <label for="gallery" class="col-form-label">Image Gallery<span class="text-danger">*</span></label>
  <div class="input-group">
      <span class="input-group-btn">
          <button class="btn btn-secondary text-white" type="button" onclick="document.getElementById('galleryInput').click();">
              <i class="fa fa-picture-o"></i> Choose
          </button>
      </span>
      <input id="galleryThumbnail" class="form-control" type="text" readonly>
  </div>
  <input type="file" id="galleryInput" accept="image/*" style="display: none;" name="gallery[]" multiple onchange="previewGalleryImages(event)">
  <div id="galleryHolder" style="margin-top: 15px; max-height: 100px;"></div>
  <span id="galleryError" class="text-danger"></span>
</div>


        {{-- <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div> --}}
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Reset</button>
           <button class="btn btn-success" type="submit">Submit</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
  function previewImage(event) {
      var input = event.target;
      var reader = new FileReader();
      reader.onload = function() {
          var dataURL = reader.result;
          var holder = document.getElementById('holder');
          holder.innerHTML = '<img src="' + dataURL + '" style="max-height: 100px;">';
          document.getElementById('thumbnail').value = input.files[0].name;
      };
      reader.readAsDataURL(input.files[0]);
  }

  function previewGalleryImages(event) {
      var input = event.target;
      var galleryHolder = document.getElementById('galleryHolder');
      galleryHolder.innerHTML = ''; // Clear previous images
      var files = input.files;
      var filenames = [];

      for (var i = 0; i < files.length; i++) {
          var reader = new FileReader();
          reader.onload = (function(file) {
              return function(e) {
                  var dataURL = e.target.result;
                  galleryHolder.innerHTML += '<img src="' + dataURL + '" style="max-height: 100px; margin-right: 10px;">';
              };
          })(files[i]);
          reader.readAsDataURL(files[i]);
          filenames.push(files[i].name);
      }

      document.getElementById('galleryThumbnail').value = filenames.join(', ');
  }
</script>

<script>
            // $('#lfm').filemanager('image');
    $(document).ready(function() {
      $('#short_desc').summernote({
        placeholder: "Write short description.....",
          tabsize: 2,
          height: 100
      });
    });

    $(document).ready(function() {
      $('#description').summernote({
        placeholder: "Write detail description.....",
          tabsize: 2,
          height: 150
      });
    });
    // $('select').selectpicker();

</script>

<script>
  $('#cat_id').change(function(){
    var cat_id=$(this).val();
    // alert(cat_id);
    if(cat_id !=null){
      // Ajax call
      $.ajax({
        url:"/admin/category/"+cat_id+"/child",
        data:{
          _token:"{{csrf_token()}}",
          id:cat_id
        },
        type:"POST",
        success:function(response){
          if(typeof(response) !='object'){
            response=$.parseJSON(response)
          }
          // console.log(response);
          var html_option="<option value=''>----Select sub category----</option>"
          if(response.status){
            var data=response.data;
            // alert(data);
            if(response.data){
              $('#child_cat_div').removeClass('d-none');
              $.each(data,function(id,title){
                html_option +="<option value='"+id+"'>"+title+"</option>"
              });
            }
            else{
            }
          }
          else{
            $('#child_cat_div').addClass('d-none');
          }
          $('#child_cat_id').html(html_option);
        }
      });
    }
    else{
    }
  })
</script>
<script>
  function toggleQuantityField() {
      var inStock = document.getElementById('inStock').checked;
      var quantityField = document.getElementById('quantityField');
      
      if (inStock) {
          quantityField.style.display = 'block';
      } else {
          quantityField.style.display = 'none';
      }
  }
</script>
@endpush