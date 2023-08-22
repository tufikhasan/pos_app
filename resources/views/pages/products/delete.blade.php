<!-- Modal -->
<div class="modal fade" id="delete_product_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="delete_product_form">
            <div class="modal-header">
                <h5 class="modal-title">Delete product</h5>
                <button type="button" class="close" onclick="closeModal('#delete_product_modal')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-danger">Are you sure want to delete this product?</p>
                <input type="hidden" id="del_product">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('#delete_product_modal')">Close</button>
                <button type="submit" class="btn btn-primary">Delete product</button>
            </div>
        </form>
    </div>
</div>
