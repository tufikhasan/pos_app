<!-- Modal -->
<div class="modal fade" id="edit_staff_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="edit_staff_form">
            <div class="modal-header">
                <h5 class="modal-title">Edit Staff</h5>
                <button type="button" class="close" onclick="closeModal('#edit_staff_modal','edit_staff_form')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="up_id">
                <div class="form-group">
                    <label for="">Name</label>
                    <div class="input-group input-group-merge input-group-alternative mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                        </div>
                        <input class="form-control" placeholder="Staff Name" type="text" id="up_name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <div class="input-group input-group-merge input-group-alternative mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                        </div>
                        <input class="form-control" placeholder="Email" type="email" id="up_email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Mobile</label>
                    <div class="input-group input-group-merge input-group-alternative mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                        </div>
                        <input class="form-control" placeholder="Mobile" type="tel" id="up_mobile">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Role</label>
                    <select class="form-control" id="up_role">
                        <option value="seller" selected>Seller</option>
                        <option value="manager">Manager</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('#edit_staff_modal','edit_staff_form')">Close</button>
                <button type="submit" class="btn btn-primary">Update Staff</button>
            </div>
        </form>
    </div>
</div>
