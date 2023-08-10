<!-- Modal -->
<div class="modal fade" id="edit_customer_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="edit_customer_form">
            <div class="modal-header">
                <h5 class="modal-title">Edit customer</h5>
                <button type="button" class="close" onclick="closeModal('#edit_customer_modal','edit_customer_form')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="up_id" name="id">
                <div class="form-group">
                    <label for="">Name</label>
                    <div class="input-group input-group-merge input-group-alternative mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                        </div>
                        <input class="form-control" placeholder="customer Name" type="text" id="up_name"
                            name="name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <div class="input-group input-group-merge input-group-alternative mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                        </div>
                        <input class="form-control" placeholder="Email" type="email" id="up_email" name="email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Mobile</label>
                    <div class="input-group input-group-merge input-group-alternative mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                        </div>
                        <input class="form-control" placeholder="Mobile" type="tel" id="up_mobile" name="mobile">
                    </div>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <div class="custom-file">
                        <input type="file" oninput="up_cus_img_preview.src=window.URL.createObjectURL(this.files[0])"
                            class="custom-file-input" id="up_image" name="image">
                    </div>
                </div>
                <img width="70" src="{{ asset('assets/img/no_image.jpg') }}" id="up_cus_img_preview">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('#edit_customer_modal','edit_customer_form')">Close</button>
                <button type="submit" class="btn btn-primary">Update customer</button>
            </div>
        </form>
    </div>
</div>
