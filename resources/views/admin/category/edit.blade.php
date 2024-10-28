@extends('admin.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Category</h1>
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

            <form action="" method="post" id="categoryForm" name="categoryForm" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" value="{{$category->name}}" class="form-control"
                                        placeholder="Name">
                                    <p class="error-name"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Slug</label>
                                    <input type="text" name="slug" id="slug" value="{{$category->slug}}" readonly class="form-control"
                                        placeholder="Slug">
                                    <p class="error-slug"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" id="image_id" name="image_id" value="">
                                    <label for="image">Image</label>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">    
                                            <br>Drop files here or click to upload.<br><br>                                            
                                        </div>
                                    </div>
                                </div>

                                @if(!empty($category->image))
                            <div>
                                    <img width="250" src="{{asset('uploads/category/thumb/'.$category->image)}}" alt="">
                            </div>
                            @endif
                            </div>

                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option selected disabled>Select Status</option>
                                        <option {{($category->status==1 ? 'selected' : '')}} value="1" >Active</option>
                                        <option {{($category->status==0 ? 'selected' : '')}} value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="showHome">Show Home</label>
                                    <select name="showHome" id="showHome" class="form-control">
                                        <option selected disabled>Select Show Home</option>
                                        <option {{($category->showHome=='Yes' ? 'selected' : '')}} value="Yes" >Yes</option>
                                        <option {{($category->showHome=='No' ? 'selected' : '')}} value="No">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
            $("#categoryForm").submit(function(event) {
                event.preventDefault();
                var element = $(this);
                $("button[type=submit]").prop('disabled', true);
                $.ajax({
                    url: '{{ route("categories.update",$category->id) }}',
                    type: 'put',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response) {
                        $("button[type=submit]").prop('disabled', false);
                        if (response["status"] == true) {
                            window.location.href = "{{ route('categories.index') }}"

                        } else {
                            if(response['notFound']==true){
                                window.location.href = "{{ route('categories.index') }}" 
                            }
                            var errors = response.errors;
                            if (errors.name) {
                                $("#name").addClass('is-invalid').siblings('p.error-name')
                                    .addClass(
                                        'invalid-feedback').html(errors.name);
                            } else {
                                $("#name").removeClass('is-invalid').siblings('p.error-name')
                                    .removeClass('invalid-feedback').html("");
                            }

                            if (errors.slug) {
                                $("#slug").addClass('is-invalid').siblings('p.error-slug')
                                    .addClass(
                                        'invalid-feedback').html(errors.slug);
                            } else {
                                $("#slug").removeClass('is-invalid').siblings('p.error-slug')
                                    .removeClass('invalid-feedback').html("");
                            }
                        }
                    },
                    error: function(jqXHR, exception) {
                        console.log("Something went wrong");
                    }
                });
            });


            // Automatic Slug Change
            $("#name").change(function() {
                element = $(this)
                $("button[type=submit]").prop('disabled', true);
                $.ajax({
                    url: '{{ route('getSlug') }}',
                    type: 'get',
                    data: {
                        title: element.val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        $("button[type=submit]").prop('disabled', false);
                        if (response['status'] == true) {
                            $('#slug').val(response['slug']);
                        }
                    }
                });

            })

            // Drop Zone 
        });



        Dropzone.autoDiscover = false;
            const dropzone = $("#image").dropzone({
                init: function() {
                    this.on('addedfile', function(file) {
                        if (this.files.length > 1) {
                            this.removeFile(this.files[0]);
                        }
                    });
                },
                url: "{{ route('temp-images.create') }}",
                maxFiles: 1,
                paramName: 'image',
                addRemoveLinks: true,
                acceptedFiles: "image/jpeg,image/png,image/gif",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(file, response) {
                    $("#image_id").val(response.image_id);
                    //console.log(response)
                }
            });
    </script>
@endsection
