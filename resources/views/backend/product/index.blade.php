@extends('backend.layouts.master')
@section('main-content')
<style>
  div.dataTables_wrapper div.dataTables_length select{
    width: 40%;
  }
  .no-arrow::after {
  display: none !important;
}
</style>
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
     <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
     <!-- Include jQuery (required for DataTables) -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <!-- Include DataTables JS -->
     <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Product Lists</h6>
        <div class="float-right d-flex">
            <form action="{{ route('product.import') }}" method="POST" enctype="multipart/form-data" class="mr-1">
                @csrf
                <label for="importFile" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Import Products">
                    <i class="fas fa-file"></i> Import File
                    <input id="importFile" type="file" name="import_file" accept=".csv,.xlsx" style="display: none;" onchange="this.form.submit()">
                </label>
            </form>
        <a href="{{route('product.create')}}" class="btn btn-primary btn-sm mx-1" data-toggle="tooltip" data-placement="bottom" title="Add Product"><i class="fas fa-plus"></i> Add Product</a>
        <a href="#" class="btn btn-primary btn-sm mx-1 refresh_btn" >   <i class="fas fa-sync"></i></a>
        </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="product-dataTable" width="100%" cellspacing="0">
          <thead>
              <tr>
                <th>SKU ID</th>
                  <th>Vendor Name</th>
                  <th>Product  Name</th>
                  {{-- <th>Category</th> --}}
                
                  {{-- <th>Regular Price</th> --}}
                  <th>Sale / List Price</th>
                  <th>Stock</th>
                  <th>Status</th>
                  <th>Action</th>
              </tr>
          </thead>
          <tbody>
              @foreach($products as $product)
              @php
                  $stock_status = $product->stock_status == 1 ? "In Stock" : ($product->stock_status == 0 ? "Out of Stock" : "On Backorder");
              @endphp
              <tr>
                <td>{{$product->sku}}</td>
                <td>{{$product->vendor ? $product->vendor->name : '' }}</td>
                  <td>{{$product->name}}  <sub>({{$product->Category->title}})</sub></td>
 
                  <td>₹{{$product->sale_price}} <sub>(₹{{$product->regular_price}})</sub> </td>
                  <td>{{$product->quantity}}</td>
                  <td>
                    <form action="{{ route('Approvel', $product->id) }}" method="POST" style="display: flex; align-items: center;">
                        @csrf
                        <select name="is_approvel" class="form-control" style="margin-right: 10px;" onchange="enableSubmitButton(this)">
                            <option value="#">Select Status</option>
                            <option value="0" {{ $product->is_approvel == 0 ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ $product->is_approvel == 1 ? 'selected' : '' }}>Approved</option>
                            <option value="2" {{ $product->is_approvel == 2 ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <button id="submit-button-{{ $product->id }}" style="background: #132644; color: white; border-radius: 6px;" type="submit" disabled>Submit</button>
                    </form>
                </td>
                <td>
                  {{-- <div style="text-align: center">
                    <a  id="actionMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                      <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="actionMenu">
                      <a class="dropdown-item" href="{{route('product.edit', $product->id)}}" data-toggle="tooltip" title="Edit" data-placement="bottom">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                      <form method="POST" action="{{route('product.destroy', $product->id)}}" style="display:inline;">
                        @csrf
                        @method('delete')
                        <button class="dropdown-item" type="submit" data-id={{$product->id}} data-toggle="tooltip" data-placement="bottom" title="Delete">
                          <i class="fas fa-trash-alt"></i> Delete
                        </button>
                      </form>
                    </div>
                  </div> --}}
                  <a href="#"><i class="fas fa-eye"></i></a>
                </td>
              </tr>
              @endforeach
          </tbody>
      </table>
    </div>
</div><!-- Visit 'codeastro' for more projects -->
@endsection
@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate{
          display: none;
      }
      .zoom {
        transition: transform .2s; /* Animation */
      }
      .zoom:hover {
        transform: scale(5);
      }
  </style>
@endpush
@push('scripts')
  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>
  <script>
      // $('#product-dataTable').DataTable( {
      //   "scrollX": false,
      //       "columnDefs":[
      //           {
      //               "orderable":false,
      //               "targets":[10,11,12]
      //           }
      //       ]
      //   } );
        // Sweet alert
        function deleteData(id){
        }

        function enableSubmitButton(selectElement) {
            const submitButton = $(selectElement).closest('form').find('button[type="submit"]');
            submitButton.prop('disabled', false);
        }
  </script>
  <script>
      $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
          $('.dltBtn').click(function(e){
            var form=$(this).closest('form');
              var dataID=$(this).data('id');
              // alert(dataID);
              e.preventDefault();
              swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                       form.submit();
                    } else {
                        swal("Your data is safe!");
                    }
                });
          })
      })
  </script>

<script>
  $(document).ready(function() {
        $('#product-dataTable').DataTable({
            "paging": true,
            "ordering": false,
            "info": true
        });
  });
</script>
 <script>
        document.querySelector('.refresh_btn').addEventListener('click', function(event) {
            event.preventDefault();
            location.reload();
        });
    </script>
@endpush
