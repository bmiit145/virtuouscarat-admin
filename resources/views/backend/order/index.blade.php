@extends('backend.layouts.master')

@push('styles')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endpush
@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Order Lists</h6>
    </div>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <div class="card-body">
      <div class="table-responsive">

        <table class="table table-bordered table-hover" id="order-dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Order Date</th>
                    <th>Order No.</th>
                    <th>Customer Name</th>
                    <th>Product Name</th>
                    <th>Vendor Name</th>
                    <th>Product Price</th>
                    <th>Order Value</th>
                    <th>Vendor Status</th>
                    <th>Customer Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    @php
                        $rowspan = count($order->products);
                    @endphp
                    @foreach($order->products as $index => $product)
                        <tr data-order_id="{{ $order->order_id }}">
                            @if($index == 0)
                                <td rowspan="{{ $rowspan }}">{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $order->order_id }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $order->billing_first_name }} {{ $order->billing_last_name }}</td>
                            @endif
                            <td>
                                @if($product->product)
                                    <span>{{ $product->product->name }} <sub>{{ $product->product->sku }}</sub></span>
                                @endif
                            </td>
                            <td>
                                @if($product->product)
                                    <span>{{ $product->product->vendor->name }}</span>
                                @endif
                            </td>
                            <td>
                                @if($product->product)
                                    <span>₹{{ $product->price }} <sub>QTY {{ $product->quantity }}</sub></span>
                                @endif
                            </td>
                            @if($index == 0)
                                <td rowspan="{{ $rowspan }}">₹{{ number_format($order->total, 2) }}</td>
                            @endif
                            <td>
                                @if($product->is_fulfilled == 1)
                                    <span class="btn btn-sm btn-success" style="cursor: unset">Approved By Vendor</span>
                                @elseif($product->is_fulfilled == 2)
                                    <span class="btn btn-sm btn-danger" style="cursor: unset">Rejected By Vendor</span>
                                @elseif($product->is_fulfilled == 3)
                                    <span class="btn btn-sm btn-info" style="cursor: unset">Pending By Vendor</span>
                                @elseif($product->is_fulfilled == 4)
                                    <span class="btn btn-sm btn-danger" style="cursor: unset">Rejected</span>
                                @elseif($product->is_fulfilled == 5)
                                    <span class="btn btn-sm btn-dark" style="cursor: unset">Cancelled</span>
                                @else
                                    <span class="btn btn-sm btn-warning" style="cursor: unset">Pending</span>
                                @endif
                            </td>
                            @if($index == 0)
                                <td rowspan="{{ $rowspan }}">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input SwitchCustomerShow" type="checkbox" role="switch" id="SwitchCustomerShow" data-toggle="toggle" @if($order->customer_status_show) checked @endif>
                                    </div>
                                </td>
                            @endif
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
                                                <option value="#">Select status</option>
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
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


  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>
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
                        if(data.status){
                            // location.reload();
                        }
                    }
                });
            });
        });
    </script>
@endpush
