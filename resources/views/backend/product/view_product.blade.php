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
        <div class="d-flex justify-content-between align-content-center">
        <h3>Product Details</h3>
{{--        <button class="btn btn-primary" onclick="window.history.back()" style="margin-bottom: .5rem">Back</button>--}}
        <button class="btn btn-primary" onclick="window.close();" style="margin-bottom: .5rem">Back</button>
        </div>
        <table class="table table-bordered">
            <tr>
                <th>SKU</th>
                <td>{{ $product->sku ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Vendor</th>
                <td>{{ $product->vendor ? $product->vendor->name : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $product->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Description</th>
                <td>{{ $product->description ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Short Description</th>
                <td>{{ $product->short_description ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Regular Price</th>
                <td>${{ $product->regular_price ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Is Approval</th>
                <td>{{ $product->is_approvel ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <th>IGI Certificate</th>
                <td>{{ $product->igi_certificate ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Main Photo</th>
                <td>
                    @if ($product->main_photo)
                        <img src="{{ $product->main_photo }}" alt="Main Photo" width="100">
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            <tr>
                <th>Photo Gallery</th>
                <td>
                    @if ($product->photo_gallery)
                        @foreach(json_decode($product->photo_gallery, true) as $photo)
                            <img src="{{ $photo }}" alt="Photo Gallery" width="100">
                        @endforeach
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            <tr>
                <th>Carat Weight</th>
                <td>{{ $product->CTS ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Video Link</th>
                <td>
                    @if ($product->video_link)
                        <a href="{{ $product->video_link }}" target="_blank">Watch Video</a>
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $product->location ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Comment</th>
                <td>{{ $product->comment ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>RAP</th>
                <td>${{ $product->RAP ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Price</th>
                <td>$ {{ $product->price ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Discount</th>
                <td>{{ $product->discount ?? 'N/A' }}%</td>
            </tr>
            <tr>
                <th>Discounted Price</th>
                <td>$ {{ $product->discounted_price ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Sale Price</th>
                <td>$ {{ $product->sale_price ?? 'N/A' }}</td>
            </tr>

            <!-- Product Attributes -->
            <tr>
                <th>Attributes</th>
                <td>
                    <div class="attributes-container">
                        @forelse($productAttributes as $attribute)
                            <div class="attribute-item">
                                <strong>{{ $attribute->name ?? 'N/A' }}:</strong> {{ $attribute->value ?? 'N/A' }}
                            </div>
                        @empty
                            N/A
                        @endforelse
                    </div>
                </td>
            </tr>

        </table>
    </div>

@endsection
