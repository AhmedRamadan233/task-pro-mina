@extends('dashboard.layouts.dashboard')

@section('title', __('Roles Page'))

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active"> {{ __('Roles Page') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary card-outline">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <form action="{{ route('albums.index') }}" method="get" class="form-inline">
                            <div class="form-group mx-2">
                                <label for="name" class="sr-only">{{ __('Search by Name') }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="name"
                                        placeholder="Search by name..." name="name" value="">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>



                            <button type="submit" class="btn btn-primary mx-2">{{ __('Search') }}</button>
                        </form>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#createPictureModal">
                                Create a new picture
                            </button>
                            {{-- <a href="{{route('albums.create')}}" class="btn btn-primary">{{__('Add New Role')}}</a> --}}
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table id="picture-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Album') }}</th>
                                <th>{{ __('Image') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pictures as $picture)
                                <tr>
                                    <td>{{ $picture->id }}</td>
                                    <td>{{ $picture->name }}</td>
                                    <td>{{ $picture->album->name }}</td>
                                    <td>
                                        {{-- <img src="{{ asset('images/' . $picture->image) }}" alt="Picture"> --}}
                                        <img src="{{ asset('storage/' . $picture->image) }}" alt="Picture">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#editPictureModal" href="javascript:;"
                                            onclick="editPicture({{ $picture->id }})">
                                            Edit
                                        </button>
                                        <button type="submit" class="btn btn-icon btn-danger"
                                            onclick="confirmDelete('{{ $picture->id }}')">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <h5 class="m-0">{{ __('Featured') }}</h5>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.pages.pictures.create')
    @include('dashboard.pages.pictures.edit')

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#createPictureForm').on('submit', function(event) {
                event.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        $('#createPictureModal').modal('hide');
                        $('#picture-table').load(location.href + ' #picture-table>*', '');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });


        function editPicture(pictureId) {
            // console.log(pictureId);
            $.ajax({
                url: '/pictures/edit/' + pictureId,
                type: 'GET',
                success: function(response) {
                    console.log(response.editPicture);
                    $('#editPictureModal').modal('show');
                    // Populate form fields with data from the response
                    $('.id').val(response.editPicture.id);
                    $('.name').val(response.editPicture.name);
                    $('.album_id').val(response.editPicture.album_id);
                    $('.image').val(response.editPicture.image);

                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }



        $('#editPictureForm').on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            formData.append('_method', 'PUT'); // Spoofing PUT method

            $.ajax({
                url: $(this).attr('action'), // This will be the generated URL with the ID
                type: 'POST', // Use POST here because browsers do not support PUT in forms
                data: formData,
                contentType: false,
                processData: false,
                headers: {},
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        $('#editPictureModal').modal('hide');
                        $('#picture-table').load(location.href + ' #picture-table>*', '');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        for (var key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                console.log(key + " validation error: " + errors[key]);
                            }
                        }
                    }
                }

            });
        });





        function confirmDelete(pictureId) {
            if (confirm("Are you sure you want to delete this picture?")) {
                $.ajax({
                    url: "{{ route('pictures.destroy', ['picture' => '__pictureId__']) }}".replace('__pictureId__',
                        pictureId),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        $('#picture-table').load(location.href + ' #picture-table>*', '');

                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            } else {
                return false;
            }
        }
    </script>
@endpush
