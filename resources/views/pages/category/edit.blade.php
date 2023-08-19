<!-- Modal -->
<div class="modal fade" id="edit_category_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="edit_category_form">
            <div class="modal-header">
                <h5 class="modal-title">Edit category</h5>
                <button type="button" class="close" onclick="closeModal('#edit_category_modal','edit_category_form')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="up_id" name="id">
                <div class="form-group">
                    <label for="">Name</label>
                    <input class="form-control" placeholder="category Name" type="text" id="up_name"
                        name="name">
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <div class="custom-file">
                        <input type="file"
                            oninput="up_category_img_preview.src=window.URL.createObjectURL(this.files[0])"
                            class="custom-file-input" id="up_image" name="image">
                    </div>
                </div>
                <img width="70" src="{{ asset('assets/img/no_image.jpg') }}" id="up_category_img_preview">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('#edit_category_modal','edit_category_form')">Close</button>
                <button type="submit" class="btn btn-primary">Update category</button>
            </div>
        </form>
    </div>
</div>
