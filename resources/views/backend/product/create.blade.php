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
        label{
            color: black
        }
    </style>
    <div class="card">
        <h5 class="card-header align-items-center">
            <span> Add Product </span>
            <a href="{{route('product.index')}}" class="btn btn-sm btn-primary shadow-sm float-right">Back</a>
        </h5>
        <div class="card-body">
            <form method="post" action="{{route('product.store')}}" id="productForm" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="form-group">
                    <label for="category_id">Category <span class="text-danger">*</span></label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">--Select any category--</option>
                        @foreach($categories as $key=>$cat_data)
                            <option value='{{$cat_data->id}}'>{{$cat_data->title}}</option>
                        @endforeach
                    </select>
                </div>

                {{--        <div class="form-group">--}}
                {{--          <label for="inputTitle" class="col-form-label">Product Name <span class="text-danger">*</span></label>--}}
                {{--          <input id="inputTitle" type="text" name="prod_name" placeholder="Enter Product Name"  value="{{old('prod_name')}}" class="form-control">--}}
                {{--          @error('prod_name')--}}
                {{--          <span class="text-danger">{{$message}}</span>--}}
                {{--          @enderror--}}
                {{--        </div>--}}


                <div class="form-group">
                    <label for="description" class="col-form-label">Description</label>
                    <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
                    @error('description')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="short_desc" class="col-form-label">Short Description</label>
                    <textarea class="form-control" id="short_desc" name="short_desc">{{old('short_desc')}}</textarea>
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

                {{--        <div class="form-group">--}}
                {{--          <label for="price" class="col-form-label">List Price(₹) <span class="text-danger">*</span></label>--}}
                {{--          <input id="price" type="number" name="price" placeholder="Enter price" min="0"  value="{{old('price')}}" class="form-control">--}}
                {{--          @error('price')--}}
                {{--          <span class="text-danger">{{$message}}</span>--}}
                {{--          @enderror--}}
                {{--        </div>--}}

                {{--        <div class="form-group">--}}
                {{--          <label for="sale_price" class="col-form-label">Sale Price(₹)</label>--}}
                {{--          <input id="sale_price" type="number" name="sale_price" min="0" placeholder="Enter Sale Price"  value="{{old('sale_price')}}" class="form-control">--}}
                {{--          @error('sale_price')--}}
                {{--          <span class="text-danger">{{$message}}</span>--}}
                {{--          @enderror--}}
                {{--        </div>--}}

                <div class="form-group">
                    <label for="CTS" class="col-form-label">Carat Weight<span class="text-danger">*</span></label>
                    <input id="CTS" type="number" name="CTS" min="0" placeholder="Enter Sale Price"  value="{{old('CTS')}}" class="form-control" step="any">
                    @error('CTS')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="RAP" class="col-form-label">RAP ( Rate Per Carat ($) )<span class="text-danger">*</span></label>
                    <input id="RAP" type="number" name="RAP" min="0" placeholder="Enter Sale Price"  value="{{old('RAP')}}" class="form-control" step="any">
                    @error('RAP')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="total_price" class="col-form-label font-weight-bold text-primary">
                        Total Price: <span id="totalPriceLabel">$0</span>
                    </label>
                </div>
                <div class="form-group">
                    <label for="discount" class="col-form-label">Discount (%)</label>
                    <input id="discount" type="number" name="discount" min="0" max="100" placeholder="Enter Sale Price"  value="{{old('discount' , 0)}}" class="form-control" step="any">
                    @error('discount')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="discounted_price" class="col-form-label font-weight-bold text-primary">
                        Discounted Price: <span id="discountedPriceLabel">$0</span>
                    </label><br/>
                    {{--              <label for="listed_price" class="col-form-label font-weight-bold text-primary">--}}
                    {{--                  Listed Price: <span id="listedPriceLabel">$0</span>--}}
                    {{--              </label>--}}
                </div>

                {{-- SKU --}}

                <div class="form-group">
                    <label for="sku" class="col-form-label">Certificate No. <span class="text-danger">*</span></label>
                    <input id="sku" type="text" name="sku" placeholder="SKU"  value="{{old('sku')}}" class="form-control">
                    @error('sku')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                {{--          Quantity --}}

                {{--        <div class="form-group">--}}
                {{--          <label for="quantity" class="col-form-label">Quantity <span class="text-danger">*</span></label>--}}
                {{--          <input id="number" type="number" name="quantity" placeholder="Quantity"  value="{{old('quantity')}}" class="form-control">--}}
                {{--          @error('quantity')--}}
                {{--          <span class="text-danger">{{$message}}</span>--}}
                {{--          @enderror--}}
                {{--        </div>--}}

                {{-- Stock Status --}}
                {{-- <label class="col-form-label">Stock Status <span class="text-danger">*</span></label>
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
              </div> --}}


                <div class="form-group">
                    <label for="IGI_certificate" class="col-form-label">IGI Certificate link<span class="text-danger">*</span></label>
                    <input id="IGI_certificate" type="text" name="IGI_certificate" placeholder="IGI Certificate Link"  value="{{old('IGI_cert')}}" class="form-control">
                    @error('IGI_certificate')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>


                {{-- Attribute --}}

                @php
                   $attributes = [
                                'Type' => 'Lab Grown Diamond',
                                'LAB' => 'IGI',
                                'Shape' => 'Round Brilliant',
                                'Carat Weight' => '0.38 ct',
                                'Cut' => 'Ideal',
                                'Color' => 'E',
                                'Fancy Color' => 'YELLOW',
                                'Fancy Color Intensity' => 'Fancy Intense',
                                'Fancy Color Overtone' => 'GREENISH',
                                'Clarity' => 'VS1',
                                'Fluorescence' => 'None',
                        //          'Growth Method' => 'CVD',
                                'Growth Type' => 'CVD',
                                'Polish' => 'Excellent',
                                'Symmetry' => 'Excellent',
                                'Measurement' => '4.68-4.71 x 2.94 mm',
                                'Table' => '56.5%',
                                'Depth' => '62.5%',
                                'Ratio' => '1.01',
                                'BGM' => 'NO',
                                'Laser Inscription' => 'YES',
                                'Member Comments' => '',
                                'Pair' => '',
                                'H&A' => '',
                                'Eye Clean' => 'YES',
                                'LOCATION' => 'MUMBAI',
                                'MILKY' => 'NO',
                                'LUSTER' => 'Ex.'
                            ];
                @endphp

                <hr/>
                <h5> Attributes </h5>
                <div class="row">
                    @foreach ($attributes as $attribute => $value)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="{{ str_replace(' ', '_', strtolower($attribute)) }}">{{ $attribute }}</label>
                                <input type="text" class="form-control" name="attributes[{{ $attribute }}]" id="{{ str_replace(' ', '_', strtolower($attribute)) }}" value="{{ old('attributes.' . $attribute, $value) }}"
                                       @if ($attribute === 'Type') disabled @endif>
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
                    @error('photo')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
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
                    @error('gallery.*')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="video_link" class="col-form-label">Video Link<span class="text-danger">*</span></label>
                    <input id="video_link" type="text" name="video_link" placeholder="Video Link"  value="{{old('video_link')}}" class="form-control">
                    @error('video_link')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
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
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
    <script>
      // validation for form as jquery
      $(document).ready(function() {
          $('#productForm').validate({
              rules: {
                  category_id: {
                      required: true
                  },
                  prod_name: {
                      required: true
                  },
                  price: {
                      required: true
                  },
                  sku: {
                      required: true
                  },
                  photo: {
                      required: true
                  },
                  gallery: {
                      required: true
                  }
              },
              messages: {
                  category_id: {
                      required: 'Please select a category'
                  },
                  prod_name: {
                      required: 'Please enter product name'
                  },
                  price: {
                      required: 'Please enter price'
                  },
                  sku: {
                      required: 'Please enter SKU'
                  },
                  photo: {
                      required: 'Please select a main photo'
                  },
                  gallery: {
                      required: 'Please select at least one image for gallery'
                  }
              }
          });
      });
    </script> -->

    <script>
        // Calculate total price
        function calculateTotalPrice() {
            var CTS = parseFloat(document.getElementById('CTS').value) || 0;
            var RAP = parseFloat(document.getElementById('RAP').value) || 0;
            var discount = parseFloat(document.getElementById('discount').value) || 0;

            var totalPrice = CTS * RAP;
            var discountedPrice = totalPrice - (totalPrice * discount / 100);
            var listedPrice = discountedPrice + (discountedPrice * 10 / 100);

            document.getElementById('totalPriceLabel').innerText = '$' + totalPrice.toFixed(2);
            document.getElementById('discountedPriceLabel').innerText = '$' + discountedPrice.toFixed(2);
            document.getElementById('listedPriceLabel').innerText = '$' + listedPrice.toFixed(2);
        }

        // Calculate total price on input change
        document.getElementById('CTS').addEventListener('input', calculateTotalPrice);
        document.getElementById('RAP').addEventListener('input', calculateTotalPrice);
        document.getElementById('discount').addEventListener('input', calculateTotalPrice);

        // Calculate total price on page load
        calculateTotalPrice();


        // show Caret Weight at attribute
        document.getElementById('CTS').addEventListener('input', function() {
            var CTS = parseFloat(document.getElementById('CTS').value) || 0;
            document.getElementById('carat_weight').value = CTS + ' ct';
        });
    </script>
@endpush
