@extends('backend.layouts.master')

@section('main-content')
    <div class="card">
        <h5 class="card-header">Edit Product</h5>
        <div class="card-body">
            <form method="post" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data">>
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="category_id">Category <span class="text-danger">*</span></label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">--Select any category--</option>
                        @foreach ($categories as $key => $cat_data)
                            <option value='{{ $cat_data->id }}'
                                {{ $cat_data->id == $product->category_id ? 'selected' : '' }}>{{ $cat_data->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Product Name <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="prod_name" placeholder="Enter Product Name"
                        value="{{ $product->name }}" class="form-control">
                    @error('prod_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="description" class="col-form-label">Description</label>
                    <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="short_desc" class="col-form-label">Short Description<span
                            class="text-danger">*</span></label>
                    <textarea class="form-control" id="short_desc" name="short_desc">{{ $product->short_description }}</textarea>
                    @error('short_desc')
                        <span class="text-danger">{{ $message }}</span>
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
              @foreach ($parent_cats as $key => $parent_cat)
                  <option value='{{$parent_cat->id}}'>{{$parent_cat->title}}</option>
              @endforeach
          </select>
        </div> --}}

                <div class="form-group">
                    <label for="price" class="col-form-label">Regular Price($) <span class="text-danger">*</span></label>
                    <input id="price" type="number" name="price" placeholder="Enter price" min="0"
                        value="{{ $product->regular_price }}" class="form-control">
                    @error('price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sale_price" class="col-form-label">Sale Price($)</label>
                    <input id="sale_price" type="number" name="sale_price" min="0" placeholder="Enter Sale Price"
                        value="{{ $product->sale_price }}" class="form-control">
                    @error('sale_price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- SKU --}}

                <div class="form-group">
                    <label for="sku" class="col-form-label">SKU <span class="text-danger">*</span></label>
                    <input id="sku" type="text" name="sku" placeholder="SKU" value="{{ $product->sku }}"
                        class="form-control">
                    @error('sku')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Stock Status --}}
                <label class="col-form-label">Stock Status <span class="text-danger">*</span></label>
                <div class="container mt-4">
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="productStatus" id="inStock"
                                value="1" onclick="toggleQuantityField()"
                                {{ $product->stock_status == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="inStock">
                                In Stock
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="productStatus" id="outOfStock"
                                value="0" onclick="toggleQuantityField()"
                                {{ $product->stock_status == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="outOfStock">
                                Out of Stock
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="productStatus" id="onBackOrder"
                                value="2" onclick="toggleQuantityField()"
                                {{ $product->stock_status == 2 ? 'checked' : '' }}>
                            <label class="form-check-label" for="onBackOrder">
                                On Backorder
                            </label>
                        </div>
                    </div>
                    <div class="form-group" id="quantityField" style="display: none;">
                        <label for="quantity" class="col-form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" min="1"
                            value="{{ $product->quantity }}">
                    </div>
                </div>


                <div class="form-group">
                    <label for="IGI_certificate" class="col-form-label">IGI Certificate link<span
                            class="text-danger">*</span></label>
                    <input id="IGI_certificate" type="text" name="IGI_certificate" placeholder="IGI Certificate Link"
                        value="{{ $product->igi_certificate }}" class="form-control">
                    @error('IGI_certificate')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="document_number" class="col-form-label">Document Number<span
                            class="text-danger">*</span></label>
                    <input id="IGI_certificate" type="text" name="document_number" placeholder="Document Number"
                        value="{{ $product->document_number }}" class="form-control">
                    @error('document_number')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Attribute --}}

                @php
                    // $attributes = [
                    //     'Type' => 'Lab Grown Diamond',
                    //     'Shape' => 'Round Brilliant',
                    //     'Carat Weight' => '0.38 ct',
                    //     'Cut' => 'Ideal',
                    //     'Colour' => 'E',
                    //     'Clarity' => 'VS1',
                    //     'Fluorescence' => 'None',
                    //     'Availability' => 'Online Only',
                    //     'Growth Method' => 'CVD',
                    //     'Polish' => 'Excellent',
                    //     'Symmetry' => 'Excellent',
                    //     'Table' => '56.5%',
                    //     'Depth' => '62.5%',
                    //     'Ratio' => '1.01',
                    // ];

                    $attributes = $product->attributes;
                @endphp

                <hr />
                <h5> Attributes </h3>
                    <div class="row">
                        @foreach ($attributes as $key => $attribute)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label
                                        for="{{ str_replace(' ', '_', strtolower($attribute['name'])) }}">{{ $attribute['name'] }}</label>
                                    <input type="text" class="form-control"
                                        name="attributes[{{ $attribute['name'] }}]"
                                        id="{{ str_replace(' ', '_', strtolower($attribute['name'])) }}"
                                        value="{{ old('attributes.' . $attribute['name'], $attribute['value']) }}">
                                </div>
                            </div>

                            @if ($loop->iteration % 3 == 0 && !$loop->last)
                    </div>
                    <div class="row">
                        @endif
                        @endforeach
                    </div>


                    <!-- Main Photo Input -->
                    <div class="form-group">
                        <label for="mainphoto" class="col-form-label">Main Photo<span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button class="btn btn-secondary text-white" type="button"
                                    onclick="document.getElementById('imageInput').click();">
                                    <i class="fa fa-picture-o"></i> Choose
                                </button>
                            </span>
                            <input id="thumbnail" class="form-control" type="text" readonly>
                        </div>
                        <input type="file" id="imageInput" accept="image/*" style="display: none;" name="photo"
                            onchange="previewImage(event)">

                        <div id="holder" style="margin-top: 15px; max-height: 100px;"> <img
                                src="{{ $product->main_photo }}" alt="" style="width: 4%;"></div>
                        <span id="error" class="text-danger"></span>
                    </div>

                    <!-- Image Gallery Input -->
                    <div class="form-group">
                        <label for="gallery" class="col-form-label">Image Gallery<span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button class="btn btn-secondary text-white" type="button"
                                    onclick="document.getElementById('galleryInput').click();">
                                    <i class="fa fa-picture-o"></i> Choose
                                </button>
                            </span>
                            <input id="galleryThumbnail" class="form-control" type="text" readonly>
                        </div>
                        <input type="file" id="galleryInput" accept="image/*" style="display: none;"
                            name="gallery[]" multiple onchange="previewGalleryImages(event)">
                        <div id="galleryHolder" style="margin-top: 15px; max-height: 100px;">
                            @if ($product->photo_gallery)
                                @foreach (json_decode($product->photo_gallery) as $image)
                                    <div class="position-relative d-inline-block mr-2 mb-2">
                                        <img src="{{ $image }}" class="img-thumbnail" style="height: 100px">
                                        <button type="button" data-productId="{{ $product->id }}" class="btn btn-sm btn-danger position-absolute"
                                            style="top: 5px; right: 5px;" onclick="removeImage(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                        <span id="galleryError" class="text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <button class="btn btn-success" type="submit">Update</button>
                    </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/summernote/summernote.min.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@endpush
@push('scripts')
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script src="{{ asset('backend/summernote/summernote.min.js') }}"></script>
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

        function removeImage(button) {
          buttonContainer = $(button);
            const imageContainer = button.parentNode;
            const imageUrl = imageContainer.querySelector('img').src;

            // Remove image from DOM
            imageContainer.parentNode.removeChild(imageContainer);
            productId = buttonContainer.data('productid');
            // Send AJAX request to Laravel backend to update database
            $.ajax({
                url: "{{ route('remove.gallery.image') }}",
                method: 'POST',
                data: {
                  id : productId,
                    imageUrl: imageUrl,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Image removed successfully from database');
                },
                error: function(xhr, status, error) {
                    console.error('Error removing image from database:', error);
                }
            });
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
                        galleryHolder.innerHTML += '<img src="' + dataURL +
                            '" style="max-height: 100px; margin-right: 10px;">';
                    };
                })(files[i]);
                reader.readAsDataURL(files[i]);
                filenames.push(files[i].name);
            }

            document.getElementById('galleryThumbnail').value = filenames.join(', ');
        }
    </script>
    <script>
        $('#lfm').filemanager('image');

        $(document).ready(function() {
            $('#summary').summernote({
                placeholder: "Write short description.....",
                tabsize: 2,
                height: 150
            });
        });

        $('#short_desc').summernote({
            placeholder: "Write short description.....",
            tabsize: 2,
            height: 100
        });

        $(document).ready(function() {
            $('#description').summernote({
                placeholder: "Write detail Description.....",
                tabsize: 2,
                height: 150
            });
        });
    </script>

    <script>
        var child_cat_id = '{{ $product->child_cat_id }}';
        // alert(child_cat_id);
        $('#cat_id').change(function() {
            var cat_id = $(this).val();

            if (cat_id != null) {
                // ajax call
                $.ajax({
                    url: "/admin/category/" + cat_id + "/child",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (typeof(response) != 'object') {
                            response = $.parseJSON(response);
                        }
                        var html_option = "<option value=''>--Select any one--</option>";
                        if (response.status) {
                            var data = response.data;
                            if (response.data) {
                                $('#child_cat_div').removeClass('d-none');
                                $.each(data, function(id, title) {
                                    html_option += "<option value='" + id + "' " + (
                                            child_cat_id == id ? 'selected ' : '') + ">" +
                                        title + "</option>";
                                });
                            } else {
                                console.log('no response data');
                            }
                        } else {
                            $('#child_cat_div').addClass('d-none');
                        }
                        $('#child_cat_id').html(html_option);

                    }
                });
            } else {

            }

        });

        if (child_cat_id != null) {
            $('#cat_id').change();
        }
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

        toggleQuantityField();
    </script>
@endpush
