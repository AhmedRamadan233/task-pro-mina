
<div class="modal fade" id="editPictureModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <form id="editPictureForm" action="{{ route('pictures.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="id" id="id" class="form-control id">

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control name" id="name" name="name"
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                                </div>

                            <div class="form-group">
                                <label for="album_id">Album</label>
                                <select class="form-control album_id" id="album_id" name="album_id">
                                    @foreach ($albums as $album)
                                        <option value="{{ $album->id }}">{{ $album->name }}</option>
                                    @endforeach
                                </select>
                                @error('album_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image">Picture</label>
                                <input type="file" name="image" accept="image/*" class="form-control-file image"
                                    id="image">

                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- <div style="width: 100px; height: 100px; overflow: hidden;">
                                <img src="{{ asset('storage/' . $picture->image) }}" alt="Picture"
                                    style="width: 100%; height: auto;">
                            </div> --}}
                            <button type="submit" class="btn btn-primary pt-2" id="updatePictureBtn">Save
                                changes</button>
                    </form>

                </div>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> --}}
        </div>
    </div>
</div>
