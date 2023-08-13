<!-- Modal -->
<div class="modal fade" id="delete_expense_category_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="delete_expense_category_form">
            <div class="modal-header">
                <h5 class="modal-title">Delete category</h5>
                <button type="button" class="close" onclick="closeModal('#delete_expense_category_modal')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-danger">Are you sure want to delete this users?</p>
                <input type="hidden" id="del_expense_category">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('#delete_expense_category_modal')">Close</button>
                <button type="submit" class="btn btn-primary">Delete category</button>
            </div>
        </form>
    </div>
</div>
