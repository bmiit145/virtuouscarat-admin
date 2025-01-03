@extends('backend.layouts.master')

@push('styles')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <style>
        .table tbody tr {
            transition: background-color 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        :root {
            --bs-emphasis-color-rgb : #f1f1f1;
        }
        .table tbody tr.highlight-hover > td{
            background-color: #f1f1f1 !important;
        }
    </style>
@endpush
@section('main-content')

<style>
    span.toggle-handle.btn.btn-default {
        background: #fff !important;
    }
    .toggle-off {
        background: #e6e6e6 !important;
        box-shadow: inset 0 3px 5px rgba(0,0,0,.125) !important;
        border: 1px solid #adadad !important;
    }

    .fixed-text {
        white-space: nowrap;
    }
    .table  tr th {
        font-size: 12px;
        font-weight: 600 !important;
        color: rgb(63 66 82);
        line-height: 20px !important;
        font-style: normal !IMPORTANT;
        font-family: "Poppins", sans-serif;
        text-transform: uppercase;
    }
    .table tbody tr td {
        font-size: 13px;
        /*font-weight: 600 !important;*/
        color: rgb(63 66 82);
        line-height: 20px !important;
        font-style: normal !IMPORTANT;
        font-family: "Poppins", sans-serif;
    }
    .table .toggle-off.btn {
        padding-left: 20px !important;
    }

</style>

 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Order Lists</h6>
        <div class="float-right d-flex">
                    <button type="submit" id="approve-all" class="btn btn-primary bg-info border-0 btn-sm mx-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" title="Filter All Products">
                        <span class="py-1"> <i class="fas fa-filter"></i> Filter All</span>
                    </button>

        <a href="#" class="btn btn-primary btn-sm mx-1 refresh_btn" >
            <i class="fas fa-sync"></i>
            </a>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasRightLabel">Filters</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <form class="filter-order-form" id="filterForm">
                        <!-- Status Filter -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" class="form-select" name="status">
                                <option value="">-- Select Status --</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved_by_vendor" {{ request('status') == 'approved_by_vendor' ? 'selected' : '' }}>Approved By Vendor</option>
                                <option value="rejected_by_vendor" {{ request('status') == 'rejected_by_vendor' ? 'selected' : '' }}>Rejected By Vendor</option>
                                <option value="pending_by_vendor" {{ request('status') == 'pending_by_vendor' ? 'selected' : '' }}>Pending By Vendor</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <!-- Date Range Filter -->
                        <div class="d-flex mb-3">
                        <div class="mx-1">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="mx-1">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
                        </div>
                        </div>

                        <!-- Attributes Filter -->

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Category</label>
                            <select id="status" class="form-select" name="category">
                                <option value="">-- Select Status --</option>
                                @foreach($FilterCategories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Caret Weight -->
                        <div class="d-flex mb-3">
                            <div class="mx-1">
                                <label for="min_weight" class="form-label">Min Caret Weight</label>
                                <input type="number" class="form-control" name="min_weight" id="min_weight" min="0" step="any" value="{{ request('min_weight') }}" >
                            </div>
                            <div class="mx-1">
                                <label for="max_weight" class="form-label">Max Caret Weight</label>
                                <input type="number" class="form-control" name="max_weight" id="max_weight" min="0" step="any" value="{{ request('max_weight') }}">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-secondary btn-sm w-100" onclick="resetForm()">Clear All</button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-info btn-sm w-100 text-center">Apply Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <div class="card-body">
      <div class="table-responsive">

        <table class="table table-bordered table-hover" id="order-dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <span></span>
                    <th><span class="fixed-text">Order Info</span></th>
{{--                    <th><span class="fixed-text">Order No.</span></th>--}}
                    <th><span class="fixed-text">Customer Name</span></th>
                    <th><span class="fixed-text">Product Name</span></th>
                    <th><span class="fixed-text">Vendor Name</span></th>
                    <th><span class="fixed-text">Product Price</span></th>
                    <th><span class="fixed-text">Order Value</span></th>
                    <th><span class="fixed-text">Vendor Status</span></th>
                    <th><span class="fixed-text">Customer Status</span></th>
                    <th><span class="fixed-text">Action</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    @php
                        $rowspan = count($order->products);
                    @endphp
                    @foreach($order->products as $index => $product)
                        @if($product->product)
                            @php
                                $productAttributes = $product->product->attributes->pluck('value','name');
                                $ProdColor = $productAttributes->get('Color', '');
                                $prodClarity = $productAttributes->get('Clarity', '');
                                $prodCut = $productAttributes->get('Cut', '');
                                $prodMeasurement = $productAttributes->get('Measurement', '');
                            @endphp
                        @endif
                        <tr data-order_id="{{ $order->order_id }}">
                            @if($index == 0)
                                <td rowspan="{{ $rowspan }}">{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }} <br/> {{ $order->order_id }} </td>
{{--                                <td rowspan="{{ $rowspan }}">{{ $order->order_id }}</td>--}}
                                <td rowspan="{{ $rowspan }}">{{ $order->billing_first_name }} {{ $order->billing_last_name }} <br> {{ $order->billing_email }}</td>
                            @endif

                            <td data-toggle="tooltip" title="Color : {{$ProdColor . ', Clarity : ' . $prodClarity . ', Cut : ' . $prodCut . ', Measurement : ' . $prodMeasurement}}">
                                @if($product->product)
                                    <span>{{ $product->product->sku ?? '' }} <br>
                                    <span>{{ $product->product->name }}</span>
                                @endif
                            </td>
                            <td>
                                @if($product->product)
                                    <span>{{ $product->product->vendor->name }}</span><br/>
                                    <span>{{ $product->product->vendor->phone }}</span>
                                @endif
                            </td>
                            <td>
                                @if($product->product)
                                    <span>₹{{ $product->price }} </span>
                                    <!-- <sub> ({{ $product->quantity }})</sub> -->
                                @endif
                            </td>
                            @if($index == 0)
                                <td rowspan="{{ $rowspan }}">₹{{ number_format($order->total, 2) }}</td>
                            @endif
                            <td>
                                @if($product->is_fulfilled == 1)
                                    <span class="badge bg-success-light rounded-pill" style="cursor: unset">Approved By Vendor</span>
                                @elseif($product->is_fulfilled == 2)
                                    <span class="badge bg-danger-light rounded-pill" style="cursor: unset">Rejected By Vendor</span>
                                @elseif($product->is_fulfilled == 3)
                                    <span class="badge bg-primary-light rounded-pill" style="cursor: unset">Pending By Vendor</span>
                                @elseif($product->is_fulfilled == 4)
                                    <span class="badge bg-danger-light rounded-pill" style="cursor: unset">Rejected</span>
                                @elseif($product->is_fulfilled == 5)
                                    <span class="badge bg-dark rounded-pill" style="cursor: unset">Cancelled</span>
                                @else
                                    <span class="badge bg-warning-light rounded-pill" style="cursor: unset">Pending</span>
                                @endif
                            </td>
                                @if($index == 0)
                            <td rowspan="{{ $rowspan }}">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input SwitchCustomerShow toggle_style"
                                            type="checkbox"
                                            role="switch"
                                            id="SwitchCustomerShow"
                                            data-toggle="toggle"
                                            data-on="On"
                                            data-off="Off"
                                               @if($order->fullfilled_status < 3) disabled @endif
                                            @if($order->customer_status_show) checked @endif >
                                    </div>
                            </td>
                                @endif

{{--                                @if($index == 0)--}}
{{--                                <td rowspan="{{ $rowspan }}">--}}
{{--                                    <div class="form-check">--}}
{{--                                        <input class="form-check-input SwitchCustomerShow toggle_style" type="checkbox" role="switch" id="SwitchCustomerShow" data-toggle="toggle" @if($order->customer_status_show) checked @endif>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                                @endif--}}


                            <td>
                                @php
                                    $is_actionable = $product->is_fulfilled == 1 || $product->is_fulfilled == 3 ? false : true;
                                    $is_actionable = $product->is_fulfilled == 0 ? true : false;
                                @endphp
                                @if($product->is_fulfilled != 5)
                                    @if($is_actionable)
                                        <form action="{{ route('order.update.product.status') }}" class="order-product-action-btn-form" method="POST" style="display: flex; align-items: center;">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                            <select name="order-action-select" class="form-control" style="margin-right: 10px;" onchange="enableSubmitButton(this)" onfocus="enableSubmitButton(this)">
                                                <option value="#">-- Select status --</option>
                                                <option value="3" {{ $product->is_fulfilled == 3 ? 'selected' : '' }}>Approved</option>
                                                <option value="4" {{ $product->is_fulfilled == 4 ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                            <button id="submit-button-{{ $order->order_id }}" style="background: #132644; color: white; border-radius: 6px;" type="submit" disabled>Submit</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>


      </div>
    </div>
</div>
@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
  <style>
      div.dataTables_wrapper div.dataTables_paginate{
          display: none;
      }
  </style>
@endpush

@push('scripts')

  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
  <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>

{{--   tooltip --}}
  <script>
      $(document).ready(function(){
          $('[data-toggle="tooltip"]').tooltip({
              track:true
          });
      });
    </script>
  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>
  <script>
    document.querySelector('.refresh_btn').addEventListener('click', function(event) {
        event.preventDefault();
        location.reload();
    });
</script>
  <script>
  function enableSubmitButton(selectElement) {
            const submitButton = $(selectElement).closest('form').find('button[type="submit"]');
            submitButton.prop('disabled', false);
        }
$(document).ready(function() {

      $('#order-dataTable').DataTable({
            "paging": true,
            "ordering": false,
            "info": true
        });
  });

        // Sweet alert
        function deleteData(id){

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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{--  Order status--}}
    <script>
        $(document).ready(function(){
            $('.order-action-btn-form').submit(function(e){
                e.preventDefault();
                // var action = $(this).find('select[name="order-action-select"]').val();
                var order_id = $(this).closest('tr').data('order_id');
                var status = $(this).find('select[name="order-action-select"]').val();

                var url = "{{ route('order.update.status') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        // action: action,
                        order_id: order_id,
                        status: status
                    },
                    success: function(data){
                        if(data.status){
                            location.reload();
                        }
                    }
                });
            });

            $('.order-product-action-btn-form').submit(function (e){
                e.preventDefault();
                var form = $(this);
                var order_id = form.find('input[name="order_id"]').val();
                var product_id = form.find('input[name="product_id"]').val();
                var status = form.find('select[name="order-action-select"]').val();

                var url = "{{ route('order.update.product.status') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        order_id: order_id,
                        product_id: product_id,
                        status: status
                    },
                    success: function(data){
                        if(data.status){
                            location.reload();
                        }
                    }
                });
            });


            // change customer_status_show
            $('.SwitchCustomerShow').change(function(){
                $this = $(this);
                var order_id = $(this).closest('tr').data('order_id');
                var status = $(this).prop('checked') ? 1 : 0;
                var url = "{{ route('order.update.customerShow.status') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        order_id: order_id,
                        status: status
                    },
                    success: function(data){
                        if (data.status == 'error') {
                            // Revert the switch state if there's an error
                            toastr.error(data.message);
                            location.reload();
                        } else {
                            toastr.success('Status updated successfully');
                        }
                    },
                    error: function() {
                        // Revert the switch state in case of a request failure
                        toastr.error('An error occurred while updating the status');
                        location.reload();
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('tr').hover(function() {
                var orderId = $(this).data('order_id');
                $('tr[data-order_id="' + orderId + '"]').addClass('highlight-hover');
          }, function() {
                var orderId = $(this).data('order_id');
                $('tr[data-order_id="' + orderId + '"]').removeClass('highlight-hover');
          });
      });
  </script>

    <script>
        $(function() {
            $('#SwitchCustomerShow').bootstrapToggle();
        });
    </script>
  <script>
      function resetForm() {
          // document.getElementById('filterForm').reset();

          var form = document.getElementById('filterForm');
            var elements = form.elements;
            for (var i = 0, len = elements.length; i < len; ++i) {
                elements[i].value = '';
            }
      }
  </script>
@endpush
