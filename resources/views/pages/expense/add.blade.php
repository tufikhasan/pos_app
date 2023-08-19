<!-- Modal -->
<div class="modal fade" id="add_expense_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="add_expense_form">
            <div class="modal-header">
                <h5 class="modal-title">Add New Expense</h5>
                <button type="button" class="close" onclick="closeModal('#add_expense_modal','add_expense_form')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input class="form-control" placeholder="Expense Amount" type="number" id="amount">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" cols="30" rows="10" placeholder="Expense Description"></textarea>
                </div>
                <div class="form-group">
                    <label for="expense_category_select">Expense Category</label>
                    <select class="form-control" id="expense_category_select">
                        <option value="0">Select Category</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('#add_expense_modal','add_expense_form')">Close</button>
                <button type="submit" class="btn btn-primary">Add New Expense</button>
            </div>
        </form>
    </div>
</div>
