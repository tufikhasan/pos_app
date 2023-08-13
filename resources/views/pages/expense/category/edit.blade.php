<!-- Modal -->
<div class="modal fade" id="edit_expense_category_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="edit_expense_category_form">
            <div class="modal-header">
                <h5 class="modal-title">Edit category</h5>
                <button type="button" class="close"
                    onclick="closeModal('#edit_expense_category_modal','edit_expense_category_form')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="up_id">
                <div class="form-group">
                    <label for="">Name</label>
                    <input class="form-control" placeholder="Expense Category" type="text" id="up_name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('#edit_expense_category_modal','edit_expense_category_form')">Close</button>
                <button type="submit" class="btn btn-primary">Update category</button>
            </div>
        </form>
    </div>
</div>
