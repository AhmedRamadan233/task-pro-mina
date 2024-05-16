<div class="modal fade" id="editAlbumModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
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
                    <form id="editAlbumForm" action="" method="post">
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
                            <button type="submit" class="btn btn-primary" id="updateAlbumBtn">Save changes</button>
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
