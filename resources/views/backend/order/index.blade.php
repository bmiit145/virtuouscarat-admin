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
                $shipping_charge=DB::table('shippings')->where('id',$order->shipping_id)->pluck('price');
            @endphp
                <tr data-order_id = {{ $order->order_id }}>
                    <td>{{\Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}</td>
                    <td>{{$order->order_id}}</td>
                    <td>{{$order->billing_first_name}} {{$order->billing_last_name}}</td>
                    <td>
                        @foreach($order->products as $product)
                            @if(!$product->product)
                                @continue
                            @endif
                            <span>{{  $product->product? $product->product->name : '' }}
                                <sub>{{ $product->product? $product->product->sku : '' }}</sub>
                            </span><br/>
                        @endforeach
                    </td>
                    <td>
                        @foreach($order->products as $product)
                            @if(!$product->product)
                                @continue
                            @endif
                            <span>{{  $product->product? $product->product->vendor->name : '' }}
                            </span><br/>
                        @endforeach
                    </td>
                    <td>
                        @foreach($order->products as $product)
                            @if(!$product->product)
                                @continue
                            @endif
                            <span>₹{{  $product ? $product->price : '' }}
                                <sub>QTY {{  $product ? $product->quantity : '' }}</sub>
                            </span><br/>
                        @endforeach
                    </td>
                    <td>₹{{number_format($order->total,2)}}</td>
                    <td>
                        @if($order->fullfilled_status == 1)
                            <span class="btn btn-sm btn-success" style="cursor: unset">Fullfilled</span>
                        @elseif($order->fullfilled_status == 2)
                            <span class="btn btn-sm btn-info" style="cursor: unset">Approved</span>
                        @elseif($order->fullfilled_status == 3)
                            <span class="btn btn-sm btn-success" style="cursor: unset">Approved by Vendor </span>
                        @elseif($order->fullfilled_status == 4)
                            <span class="btn btn-sm btn-danger" style="cursor: unset">Rejected</span>
                        @elseif($order->fullfilled_status == 5)
                            <span class="btn btn-sm btn-danger" style="cursor: unset">Rejected by Vendor</span>
                        @else
                            <span class="btn btn-sm btn-warning" style="cursor: unset">Pending</span>
                        @endif
                    </td>
                    <td>
{{--                        Add toggle to  status show switch --}}
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" data-toggle="toggle" @if($order->customer_status_show) checked @endif>
                        </div>
                    </td>
                    <td>
                        @php
                            $is_actionable = true;
                            foreach($order->products as $product){
                                if(!$product->product){
                                    continue;
                                }
                                if($product->is_fulfilled == 1 || $product->is_fulfilled == 2){
                                    $is_actionable = false;
                             }
                                }
                        @endphp
                        {{-- <button type="button" class="btn badge badge-success order-action-btn" data-action="approve"> Approve </button>
                        <button type="button" class="btn badge badge-danger order-action-btn" data-action="reject"> Reject </button> --}}
                        <form action="{{ route('order.update.status') }}" class="order-action-btn-form" method="POST" style="display: flex; align-items: center;">
                            @csrf
                            <select name="order-action-select" class="form-control" style="margin-right: 10px;" onchange="enableSubmitButton(this)" onfocus="enableSubmitButton(this)">
                                @if($is_actionable)
                                <option value="2" {{ $order->fullfilled_status == 2 ? 'selected' : '' }}>Approved</option>
                                @endif
                                <option value="4" {{ $order->fullfilled_status == 4 ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <button id="submit-button-{{ $product->id }}" style="background: #132644; color: white; border-radius: 6px;" type="submit" disabled>Submit</button>
                        </form>
                    </td>
                </tr>
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
      $('#order-dataTable').DataTable();
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
        });
    </script>
@endpush
