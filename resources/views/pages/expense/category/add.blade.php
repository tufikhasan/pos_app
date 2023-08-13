<!-- Modal -->
<div class="modal fade" id="add_expense_category_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="add_expense_category_form">
            <div class="modal-header">
                <h5 class="modal-title">Add New category</h5>
                <button type="button" class="close"
                    onclick="closeModal('#add_expense_category_modal','add_expense_category_form')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Name</label>
                    <input class="form-control" placeholder="Expense Category" type="text" id="name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('#add_expense_category_modal','add_expense_category_form')">Close</button>
                <button type="submit" class="btn btn-primary">Add New category</button>
            </div>
        </form>
    </div>
</div>
