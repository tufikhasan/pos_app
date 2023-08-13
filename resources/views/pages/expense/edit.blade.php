<!-- Modal -->
<div class="modal fade" id="edit_expense_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="edit_expense_form">
            <div class="modal-header">
                <h5 class="modal-title">Edit Expense</h5>
                <button type="button" class="close" onclick="closeModal('#edit_expense_modal','edit_expense_form')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="up_id">
                <div class="form-group">
                    <label for="up_amount">Amount</label>
                    <input class="form-control" placeholder="Expense Amount" type="number" id="up_amount">
                </div>
                <div class="form-group">
                    <label for="up_description">Description</label>
                    <textarea class="form-control" id="up_description" cols="30" rows="10" placeholder="Expense Description"></textarea>
                </div>
                <div class="form-group">
                    <label for="up_expense_category_select">Expense Category</label>
                    <select class="form-control" id="up_expense_category_select"></select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('#edit_expense_modal','edit_expense_form')">Close</button>
                <button type="submit" class="btn btn-primary">Update Expense</button>
            </div>
        </form>
    </div>
</div>
