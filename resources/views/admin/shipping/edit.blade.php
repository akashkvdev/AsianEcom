@extends('admin.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shipping Management -Update</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="categories.html" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <form action="" method="post" id="shippingUpdateForm" name="shippingUpdateForm"
                enctype="multipart/form-data">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    {{-- <label for="showHome"></label> --}}
                                    <select name="country" id="country" class="form-control">
                                        <option selected disabled>Select Country</option>
                                        @if ($countries->isNotEmpty())
                                            @foreach ($countries as $country)
                                                <option {{ $shippingCharge->country_id == $country->id ? 'selected' : '' }}
                                                    value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                            <option {{ $shippingCharge->country_id == 'rest_of_world' ? 'selected' : '' }}
                                                value="rest_of_world">Rest Of World</option>
                                        @endif

                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    {{-- <label for="name">Name</label> --}}
                                    <input type="text" name="amount" id="amount" class="form-control"
                                        placeholder="amount" value="{{ $shippingCharge->amount }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customjs')
    <script>
        $(document).ready(function() {
            $("#shippingUpdateForm").submit(function(event) {
                event.preventDefault();
                var element = $(this);
                $("button[type=submit]").prop('disabled', true);
                $.ajax({
                    url: "{{ route('shiping.update', $shippingCharge->id) }}",
                    type: 'put',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response) {
                        $("button[type=submit]").prop('disabled', false);
                        if (response["status"] == true) {
                            window.location.href = "{{ route('shipping.create') }}"

                        } else {
                            var errors = response.errors;
                            if (errors.country) {
                                $("#country").addClass('is-invalid').siblings('p')
                                    .addClass(
                                        'invalid-feedback').html(errors.country);
                            } else {
                                $("#country").removeClass('is-invalid').siblings(
                                        'p.error-country')
                                    .removeClass('invalid-feedback').html("");
                            }

                            if (errors.amount) {
                                $("#amount").addClass('is-invalid').siblings('p')
                                    .addClass(
                                        'invalid-feedback').html(errors.amount);
                            } else {
                                $("#amount").removeClass('is-invalid').siblings('p')
                                    .removeClass('invalid-feedback').html("");
                            }
                        }
                    },
                    error: function(jqXHR, exception) {
                        console.log("Something went wrong");
                    }
                });
            });
        });

      
    </script>
@endsection
