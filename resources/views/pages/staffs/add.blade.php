<!-- Modal -->
<div class="modal fade" id="add_staff_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="add_staff_form">
            <div class="modal-header">
                <h5 class="modal-title">Add New Staff</h5>
                <button type="button" class="close" onclick="closeModal('#add_staff_modal','add_staff_form')">
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
                        <input class="form-control" placeholder="Staff Name" type="text" id="name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <div class="input-group input-group-merge input-group-alternative mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                        </div>
                        <input class="form-control" placeholder="Email" type="email" id="email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Mobile</label>
                    <div class="input-group input-group-merge input-group-alternative mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                        </div>
                        <input class="form-control" placeholder="Mobile" type="tel" id="mobile">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Role</label>
                    <select class="form-control" id="role">
                        <option value="seller" selected>Seller</option>
                        <option value="manager">Manager</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <div class="input-group input-group-merge input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                        </div>
                        <input class="form-control" placeholder="Password" type="password" id="password">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('#add_staff_modal','add_staff_form')">Close</button>
                <button type="submit" class="btn btn-primary">Add New Staff</button>
            </div>
        </form>
    </div>
</div>
