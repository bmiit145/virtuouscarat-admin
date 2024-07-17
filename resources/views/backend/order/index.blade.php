@extends('backend.layouts.master')

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
              <th>Order Value</th>
              <th>Customer Status</th>
                <th>Vendor Status</th>
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
                                <sub>{{  $product->product? $product->product->sku : '' }}</sub>
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

                    <td>${{number_format($order->total,2)}}</td>
                    <td></td>
                    <td>
                        @if($order->fullfilled_status == 1)
                            <span class="badge badge-success">Fullfilled</span>
                        @elseif($order->fullfilled_status == 2)
                            <span class="badge badge-info">Passed to Vendor</span>
                        @elseif($order->fullfilled_status == 3)
                            <span class="badge badge-secondary">Processed by Vendor </span>
                        @elseif($order->fullfilled_status == 4)
                            <span class="badge badge-danger">Rejected</span>
                        @elseif($order->fullfilled_status == 5)
                            <span class="badge badge-warning">Rejected by Vendor</span>
                        @else
                            <span class="badge badge-dark ">Not Fullfilled</span>
                        @endif
                    </td>
                    <td>
                        {{-- <button type="button" class="btn badge badge-success order-action-btn" data-action="approve"> Approve </button>
                        <button type="button" class="btn badge badge-danger order-action-btn" data-action="reject"> Reject </button> --}}
                        <form action="{{ route('Approvel', $product->id) }}" method="POST" style="display: flex; align-items: center;">
                            @csrf
                            <select name="is_approvel" class="form-control" style="margin-right: 10px;" onchange="enableSubmitButton(this)">
                             
                                <option value="1" {{ $product->is_approvel == 1 ? 'selected' : '' }}>Approved</option>
                                <option value="2" {{ $product->is_approvel == 2 ? 'selected' : '' }}>Rejected</option>
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
            $('.order-action-btn').click(function(){
                var action = $(this).data('action');
                var order_id = $(this).closest('tr').data('order_id');
                var status = 0;
                if(action == 'approve'){
                    status = 2;
                }else if(action == 'reject'){
                    status = 4;
                }else if(action == 'fullfilled') {
                    status = 1;
                }

                var url = "{{ route('order.update.status') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        action: action,
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
