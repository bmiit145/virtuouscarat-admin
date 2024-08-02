@extends('backend.layouts.master')
@section('main-content')

<style>
    .attributes-container {
        display: flex;
        flex-direction: column;
    }

    .attribute-item {
        margin-bottom: 8px; 
    }
</style>

<div class="product-details">
    <h1>Product Details</h1>

    <table class="table table-bordered">
        <tr>
            <th>SKU</th>
            <td>{{ $product->sku }}</td>
        </tr>
        <tr>
            <th>Vendor</th>
            <td>{{ $product->vendor ? $product->vendor->name : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $product->name }}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $product->description }}</td>
        </tr>
        <tr>
            <th>Short Description</th>
            <td>{{ $product->short_description }}</td>
        </tr>
        <tr>
            <th>Regular Price</th>
            <td>${{ $product->regular_price }}</td>
        </tr>
        <tr>
            <th>Is Approval</th>
            <td>{{ $product->is_approvel ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <th>IGI Certificate</th>
            <td>{{ $product->igi_certificate }}</td>
        </tr>
        <tr>
            <th>Main Photo</th>
            <td><img src="{{ $product->main_photo }}" alt="Main Photo" width="100"></td>
        </tr>
        <tr>
            <th>Photo Gallery</th>
            <td>
                @foreach(json_decode($product->photo_gallery, true) as $photo)
                    <img src="{{ $photo }}" alt="Photo Gallery" alt="Photo Gallery" width="100">
                @endforeach
            </td>
        </tr>
        <tr>
            <th>Carat weight</th>
            <td>{{ $product->CTS }}</td>
        </tr>
        <tr>
            <th>Video Link</th>
            <td><a href="{{ $product->video_link }}" target="_blank">Watch Video</a></td>
        </tr>
        <tr>
            <th>Location</th>
            <td>{{ $product->location }}</td>
        </tr>
        <tr>
            <th>Comment</th>
            <td>{{ $product->comment }}</td>
        </tr>
        <tr>
            <th>RAP</th>
            <td>${{ $product->RAP }}</td>
        </tr>
        <tr>
            <th>Price</th>
            <td>${{ $product->price }}</td>
        </tr>
        <tr>
            <th>Discount</th>
            <td>{{ $product->discount }}%</td>
        </tr>
        <tr>
            <th>Discounted Price</th>
            <td>${{ $product->discounted_price }}</td>
        </tr>
        <tr>
            <th>Sale Price</th>
            <td>${{ $product->sale_price }}</td>
        </tr>

        <!-- Product Attributes -->
        <tr>
            <th>Attributes</th>
            <td>
                <div class="attributes-container">
                    @foreach($productAttributes as $attribute)
                        <div class="attribute-item">
                            <strong>{{ $attribute->name }}:</strong> {{ $attribute->value }}
                        </div>
                    @endforeach
                </div>
            </td>
        </tr>

    </table>
</div>

@endsection
