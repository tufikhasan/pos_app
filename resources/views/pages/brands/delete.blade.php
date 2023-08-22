<!-- Modal -->
<div class="modal fade" id="delete_brand_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="delete_brand_form">
            <div class="modal-header">
                <h5 class="modal-title">Delete brand</h5>
                <button type="button" class="close" onclick="closeModal('#delete_brand_modal')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-danger">Are you sure want to delete this brand?</p>
                <input type="hidden" id="del_brand">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('#delete_brand_modal')">Close</button>
                <button type="submit" class="btn btn-primary">Delete brand</button>
            </div>
        </form>
    </div>
</div>
