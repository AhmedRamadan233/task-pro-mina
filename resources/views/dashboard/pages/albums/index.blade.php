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
                                data-target="#createAlbumModal">
                                Create a new album
                            </button>
                            {{-- <a href="{{route('albums.create')}}" class="btn btn-primary">{{__('Add New Role')}}</a> --}}
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table id="album-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($albums as $album)
                                <tr>
                                    <td>{{ $album->id }}</td>
                                    <td>{{ $album->name }}</td>

                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#editAlbumModal" href="javascript:;"
                                            onclick="editAlbum({{ $album->id }})">
                                            Edit
                                        </button>
                                        <button type="submit" class="btn btn-icon btn-danger"
                                            onclick="confirmDelete('{{ $album->id }}')">
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
    <!-- Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this album?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Choices Modal -->
    <div class="modal fade" id="choicesModal" tabindex="-1" role="dialog" aria-labelledby="choicesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="choicesModalLabel">Choose an Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="choicesModalBody">
                    <!-- Choice message will be displayed here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="deleteAllBtn">Delete All Pictures</button>
                    <button type="button" class="btn btn-primary" id="movePicturesBtn">Move Pictures to Another
                        Album</button>
                </div>
            </div>
        </div>
    </div>




    <!-- Select Album Modal -->
    <div class="modal fade" id="selectAlbumModal" tabindex="-1" role="dialog" aria-labelledby="selectAlbumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectAlbumModalLabel">Select Target Album</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="targetAlbumSelect">Choose a target album:</label>
                    <select class="form-control" id="targetAlbumSelect">
                        @foreach ($albums as $album)
                            <option value="{{ $album->id }}">{{ $album->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="movePicturesToAlbumBtn">Move Pictures</button>
                </div>
            </div>
        </div>
    </div>
    @include('dashboard.pages.albums.create')
    @include('dashboard.pages.albums.edit')


@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#createAlbumForm').on('submit', function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        $('#createAlbumModal').modal('hide');
                        $('#album-table').load(location.href + ' #album-table>*', '');
                        $('#createAlbumForm').load(location.href + ' #createAlbumForm>*', '');
                        $('#selectAlbumModal').load(location.href + ' #selectAlbumModal>*', '');

                    },
                    error: function(xhr, status, error) {

                        console.error(xhr.responseText);
                    }
                });
            });

        });

        function editAlbum(albumId) {
            console.log(albumId);
            $.ajax({
                url: '/albums/edit/' + albumId,
                type: 'GET',
                success: function(response) {
                    console.log(response.editAlbum.name);

                    $('.id').val(response.editAlbum.id);
                    $('.name').val(response.editAlbum.name);
                    $('#editAlbumModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }


        $(document).ready(function() {
            $('#updateAlbumBtn').click(function(e) {
                e.preventDefault();
                var AlbumId = $('#id').val();
                var formData = $('#editAlbumForm').serialize();
                formData += '&id=' + AlbumId;
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ route('albums.update') }}",
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#editAlbumModal').modal('hide');
                            $('#album-table').load(location.href + ' #album-table>*', '');
                            $('#selectAlbumModal').load(location.href + ' #selectAlbumModal>*', '');

                        }
                    },
                    error: function(xhr, status, error) {}
                });

            });

        });



        function confirmDelete(albumId) {
            $('#confirmDeleteModal').modal('show'); // Show the modal

            $('#confirmDeleteBtn').on('click', function() {
                $.ajax({
                    url: "{{ route('albums.destroy', ['album' => '__albumId__']) }}".replace('__albumId__',
                        albumId),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.choices) {
                            // Present the user with choices
                            $('#confirmDeleteModal').modal('hide'); // Hide the modal
                            $('#choicesModal').modal('show'); // Show the choices modal
                            $('#choicesModalBody').text(response.message);
                            $('#deleteAllBtn').on('click', function() {
                            deleteAllPictures(albumId);
                            $('#choicesModal').modal('hide');
                            $('#album-table').load(location.href + ' #album-table>*', '');
                            $('#selectAlbumModal').load(location.href + ' #selectAlbumModal>*', '');


                            });
                            $('#movePicturesBtn').on('click', function() {
                                movePicturesToAnotherAlbum(albumId);
                                $('#choicesModal').modal('hide');
                                $('#album-table').load(location.href + ' #album-table>*', '');
                                $('#selectAlbumModal').load(location.href + ' #selectAlbumModal>*', '');

                            });
                        } else {
                            // Album was deleted successfully
                            $('#confirmDeleteModal').modal('hide'); // Hide the modal
                            $('#choicesModal').modal('hide');
                            $('#album-table').load(location.href + ' #album-table>*', '');
                            $('#selectAlbumModal').load(location.href + ' #selectAlbumModal>*', '');

                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        }

        function deleteAllPictures(albumId) {
            $.ajax({
                url: "{{ route('albums.deleteAllPictures', ['album' => '__albumId__']) }}".replace('__albumId__',
                    albumId),
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    $('#choicesModal').modal('hide');
                    $('#album-table').load(location.href + ' #album-table>*', '');
                    $('#selectAlbumModal').load(location.href + ' #selectAlbumModal>*', '');

                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    // Handle error response, if needed
                }
            });
        }

        function movePicturesToAnotherAlbum(albumId) {
            // Show the modal with select dropdown
            $('#selectAlbumModal').modal('show');

            // Listen for the click event on the "Move Pictures" button in the modal
            $('#movePicturesToAlbumBtn').on('click', function() {
                // Get the selected target album id
                var targetAlbumId = $('#targetAlbumSelect').val();

                // Send AJAX request to move pictures to the selected album
                $.ajax({
                    url: "{{ route('albums.movePicturesToAnotherAlbum', ['album' => '__albumId__']) }}"
                        .replace('__albumId__', albumId),
                    type: 'POST',
                    data: {
                        target_album_id: targetAlbumId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        $('#choicesModal').modal('hide');
                        $('#album-table').load(location.href + ' #album-table>*', '');
                        $('#selectAlbumModal').modal('hide');
                        // Handle success response, if needed
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        // Handle error response, if needed
                    }
                });
            });
        }
        // $(document).ready(function() {
        //     // Fetch list of albums and populate the select dropdown
        //     $.ajax({
        //         url: "{{ route('albums.index') }}", // Assuming you have an index route for albums
        //         type: 'GET',
        //         success: function(response) {
        //             console.log(response)
        //             var albums = response.albums; // Assuming response contains an array of albums
        //             var selectOptions = '';

        //             // Loop through albums and create select options
        //             $.each(albums, function(index, album) {
        //                 selectOptions += '<option value="' + album.id + '">' + album.name + '</option>';
        //             });

        //             // Append options to the select dropdown
        //             $('#targetAlbumSelect').append(selectOptions);
        //         },
        //         error: function(xhr) {
        //             console.log(xhr.responseText);
        //             // Handle error response, if needed
        //         }
        //     });
        // });
    </script>
@endpush
