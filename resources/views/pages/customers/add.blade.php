<!-- Modal -->
<div class="modal fade" id="add_customer_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="add_customer_form">
            <div class="modal-header">
                <h5 class="modal-title">Add New customer</h5>
                <button type="button" class="close" onclick="closeModal('#add_customer_modal','add_customer_form')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Name</label>
                    <div class="input-group input-group-merge input-group-alternative mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                        </div>
                        <input class="form-control" placeholder="customer Name" type="text" name="name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <div class="input-group input-group-merge input-group-alternative mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                        </div>
                        <input class="form-control" placeholder="Email" type="email" name="email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Mobile</label>
                    <div class="input-group input-group-merge input-group-alternative mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                        </div>
                        <input class="form-control" placeholder="Mobile" type="tel" name="mobile">
                    </div>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <div class="custom-file">
                        <input type="file" oninput="cus_img_preview.src=window.URL.createObjectURL(this.files[0])"
                            class="custom-file-input" id="image" name="image">
                    </div>
                </div>
                <img width="70" src="{{ asset('assets/img/no_image.jpg') }}" id="cus_img_preview">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('#add_customer_modal','add_customer_form')">Close</button>
                <button type="submit" class="btn btn-primary">Add New customer</button>
            </div>
        </form>
    </div>
</div>
