@extends('front.layouts.app')
@section('content')

    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.shop')}}">Shop</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <form id="orderForm" method="POST">
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Shipping Address</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                placeholder="First Name" value="{{(!empty($customerAddress))? $customerAddress->first_name:''}}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="Last Name" value="{{(!empty($customerAddress))? $customerAddress->last_name:''}}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control"
                                                placeholder="Email" value="{{(!empty($customerAddress))? $customerAddress->last_name:''}}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="country" id="country" class="form-control">
                                                <option selected disabled>Select a Country</option>
                                                @if ($countries->isNotEmpty())
                                                    @foreach ($countries as $country)
                                                        <option {{(!empty($customerAddress && $customerAddress->country_id==$country->id))? 'selected':''}} value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control" >{{(!empty($customerAddress))? $customerAddress->address:''}}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="apartment" id="apartment" class="form-control"
                                                placeholder="Apartment, suite, unit, etc. (optional)" value="{{(!empty($customerAddress))? $customerAddress->apartment:''}}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control"
                                                placeholder="City" value="{{(!empty($customerAddress))? $customerAddress->city:''}}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="state" id="state" class="form-control"
                                                placeholder="State" value="{{(!empty($customerAddress))? $customerAddress->state:''}}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control"
                                                placeholder="Zip" value="{{(!empty($customerAddress))? $customerAddress->zip:''}}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control"
                                                placeholder="Mobile No." value="{{(!empty($customerAddress))? $customerAddress->mobile:''}}">
                                            <p></p>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)"
                                                class="form-control" >{{(!empty($customerAddress))? $customerAddress->notes:''}}</textarea>
                                            <p></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Order Summery</h3>
                        </div>
                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach (Cart::content() as $item)
                                    <div class="d-flex justify-content-between pb-2">
                                        <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                                        <div class="h6"> ₹{{ $item->price * $item->qty }}</div>
                                    </div>
                                @endforeach

                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    <div class="h6"><strong> ₹{{ Cart::subtotal() }}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Shipping</strong></div>
                                    <div class="h6"><strong id="shippingAmount"> ₹{{number_format($totalShippingCharge,2)}}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div class="h5"><strong id="grandTotal"> ₹ {{ $grandTotal }}</strong></div>
                                </div>
                            </div>
                        </div>

                        <div class="card payment-form">
                            <h3 class="card-title h5 mb-3">Payment Details</h3>

                            <div class="mb-3">
                                <input checked type="radio" name="payment_method" value="cod"
                                    id="payment_method_cod">
                                {{-- <p></p> --}}
                                <label for="payment_method_cod" class="form-check-label mb-3">COD</label>
                            </div>
                            <div class="mb-3">
                                <input type="radio" name="payment_method" value="stripe" id="payment_method_stripe">
                                <label for="payment_method_stripe" class="form-check-label mb-3">Stripe</label>
                            </div>

                            <div class="card-body p-0 d-none" id="card-payment-form">
                                <div class="mb-3">
                                    <label for="card_number" class="mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number"
                                        placeholder="Valid Card Number" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">Expiry Date</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cvv_code" class="mb-2">CVV Code</label>
                                        <input type="text" name="cvv_code" id="cvv_code" placeholder="123"
                                            class="form-control">
                                    </div>
                                </div>

                            </div>
                            <div class="pt-4">
                                {{-- <a href="#" class="btn-dark btn btn-block w-100">Pay Now</a> --}}
                                <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                            </div>
                        </div>


                        <!-- CREDIT CARD FORM ENDS HERE -->

                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection


@section('customjs')
    <script>
        $(document).ready(function() {
            // Toggle card payment form based on payment method selection
            $("#payment_method_cod").click(function() {
                if ($(this).is(":checked")) {
                    $("#card-payment-form").addClass('d-none');
                }
            });

            $("#payment_method_stripe").click(function() {
                if ($(this).is(":checked")) {
                    $("#card-payment-form").removeClass('d-none');
                }
            });

            // Handle form submission with AJAX
            $('#orderForm').submit(function(e) {
                e.preventDefault(); // Prevent the default form submission
                $.ajax({
                    url: "{{ route('front.processCheckout') }}",
                    type: "post",
                    data: $(this).serializeArray(), // Serialize form data properly
                    dataType: "json",
                    success: function(response) {
                        // Handle success response (you can add custom actions here)
                        var errors = response.errors;
                        if (response.status == false) {
                            if (errors.first_name) {
                                $('#first_name').addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.first_name);

                            } else {
                                $('#first_name').removeClass('is-invalid').siblings('p')
                                    .removeClass('invalid-feedback').html('');
                            }
                            if (errors.last_name) {
                                $('#last_name').addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.last_name);

                            } else {
                                $('#last_name').removeClass('is-invalid').siblings('p')
                                    .removeClass('invalid-feedback').html('');
                            }
                            if (errors.email) {
                                $('#email').addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.email);

                            } else {
                                $('#email').removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback').html('');
                            }
                            if (errors.country) {
                                $('#country').addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.country);

                            } else {
                                $('#country').removeClass('is-invalid').siblings('p')
                                    .removeClass('invalid-feedback').html('');
                            }
                            if (errors.address) {
                                $('#address').addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.address);

                            } else {
                                $('#address').removeClass('is-invalid').siblings('p')
                                    .removeClass('invalid-feedback').html('');
                            }
                            if (errors.city) {
                                $('#city').addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.city);

                            } else {
                                $('#city').removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback').html('');
                            }
                            if (errors.state) {
                                $('#state').addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.state);

                            } else {
                                $('#state').removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback').html('');
                            }
                            if (errors.zip) {
                                $('#zip').addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.zip);

                            } else {
                                $('#zip').removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback').html('');
                            }
                            if (errors.mobile) {
                                $('#mobile').addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.mobile);

                            } else {
                                $('#mobile').removeClass('is-invalid').siblings('p')
                                    .removeClass('invalid-feedback').html('');
                            }
                        }else{
                            window.location.href="{{url('/thank-you/')}}/"+response.orderId;
                        }

                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        // Handle error (you can add custom error handling here)
                        console.log(xhr.responseText);
                    }
                });
            });
        });

        $('#country').change(function(){
            $.ajax({
                url:'{{route('front.getOrderSummery')}}',
                type:'post',
                data:{country_id:$(this).val()},
                dataType:'json',
                success:function(response){
                    if(response.status==true){
                        $('#shippingAmount').html('₹'+response.shippingCharge)
                        $('#grandTotal').html('₹'+response.grandTotal)
                    }
                }
            });
        })
    </script>
@endsection
