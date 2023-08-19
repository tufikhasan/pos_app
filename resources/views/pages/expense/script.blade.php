<script>
    getExpenses()
    async function getExpenses() {
        const user_id = "{{ $user_id }}";
        const user_role = "{{ $user_role }}";
        try {
            let tableList = $("#expense_list");
            let tableData = $(".table");

            tableData.DataTable().destroy();
            tableList.empty();

            showLoader();


            const URL = "{{ route('expenses') }}";
            const res = await axios.get(URL);
            hideLoader();

            res.data.forEach((expense, key) => {
                document.getElementById('expense_list').innerHTML += `
            <tr>
                <td class='align-middle'>${key + 1 < 10 ? "0" + (key + 1) : key + 1}</td>
                <td class='align-middle'>${expense['amount']}</td>
                <td class='align-middle'>${expense['description']}</td>
                <td class='align-middle'>${expense['expense_category']['name']}</td>
                <td class='align-middle'>${expense['user']['name']} - (${expense['user']['role']})</td>
                    ${['admin', 'manager'].includes(user_role) ? 
                        `<td class='align-middle'>
                            <button class="btn btn-icon btn-sm btn-info edit_expense" type="button" data-id="${expense['id']}" >
                                <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                            </button>
                            ${'admin' == user_role ? 
                                `<button class="btn btn-icon btn-sm btn-danger delete_expense" type="button" data-id="${expense['id']}" >
                                    <span class="btn-inner--icon"><i class="fas fa-trash-alt"></i></span>
                                </button>` 
                            : ''}
                        </td>`
                    : '' } 
            </tr>
            `;
            });
            tableData.DataTable({
                language: {
                    paginate: {
                        previous: "<i class='fas fa-angle-left'>",
                        next: "<i class='fas fa-angle-right'>"
                    }
                }
            })
        } catch (error) {
            hideLoader();
            console.log('Something went wrong');
            console.log(error)
        }
    }

    //show brand list & category list in add form
    populateDropdownList("{{ route('expense.categories') }}", 'expense_category_select');

    //add new Expense listener
    const add_expense_modal = document.getElementById("add_expense_modal");
    add_expense_modal.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const amount = document.getElementById('amount').value;
            const description = document.getElementById('description').value;
            const expense_category_id = document.getElementById('expense_category_select').value;

            if (0 == amount.length) {
                toastr.info("Expense Amount is Required");
            } else if (0 == description.length) {
                toastr.info("Expense Description is Required");
            } else if (0 == expense_category_id) {
                toastr.info("Expense Category is Required");
            } else {
                showLoader();
                const data = {
                    amount: amount,
                    description: description,
                    expense_category_id: expense_category_id
                };
                const addURL = "{{ route('add.expense') }}";
                const response = await axios.post(addURL, data);
                hideLoader();
                await getExpenses();
                closeModal('#add_expense_modal', 'add_expense_form');
                if (response.status == 201 && response.data.status == 'success') {
                    toastr.success(response.data.message);
                } else if (response.status == 200 && response.data.status == 'failed') {
                    toastr.error(response.data.message);
                }
            }
        } catch (error) {
            hideLoader();
            console.log('Something went wrong');
        }
    });

    //show brand list & category list in add form
    populateDropdownList("{{ route('expense.categories') }}", 'up_expense_category_select');

    async function fillExpenseEditForm(id) {
        try {
            const URL = "{{ route('single.expense', ':id') }}".replace(':id', id);
            const res = await axios.get(URL);
            document.getElementById('up_id').value = res.data['id'];
            document.getElementById('up_amount').value = res.data['amount'];
            document.getElementById('up_description').value = res.data['description'];

            // Set the selected option for "Brand"
            $("#up_expense_category_select option").each(function() {
                if ($(this).val() == res.data['expense_category_id']) {
                    $(this).attr("selected", true);
                } else {
                    $(this).attr("selected", false);
                }
            });
        } catch (error) {
            console.log("Something went wrong")
        }
    }

    // show expense id in edit form
    $(document).on('click', '.edit_expense', async function(e) {
        let id = $(this).data('id');
        showLoader();
        await fillExpenseEditForm(id);
        hideLoader();
        $('#edit_expense_modal').modal("show");
    })

    // update product listener
    const edit_expense_form = document.getElementById("edit_expense_form");
    edit_expense_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const id = document.getElementById('up_id').value;
            const amount = document.getElementById('up_amount').value;
            const description = document.getElementById('up_description').value;
            const expense_category_id = document.getElementById('up_expense_category_select').value;

            if (0 == amount.length) {
                toastr.info("Expense Amount is Required");
            } else if (0 == description.length) {
                toastr.info("Expense Description is Required");
            } else if (0 == expense_category_id) {
                toastr.info("Expense Category is Required");
            } else {
                const data = {
                    amount: amount,
                    description: description,
                    expense_category_id: expense_category_id
                }
                showLoader();
                const updateURL = "{{ route('update.expense', ':id') }}".replace(':id', id);
                const response = await axios.patch(updateURL, data);
                hideLoader();
                await getExpenses();
                closeModal('#edit_expense_modal');
                if (response.status == 200 && response.data.status == 'success') {
                    toastr.success(response.data.message);
                } else if (response.status == 200 && response.data.status == 'failed') {
                    toastr.error(response.data.message);
                }
            }
        } catch (error) {
            hideLoader();
            console.log('Internal server error');
        }
    });

    // show expense id in delete form
    $(document).on('click', '.delete_expense', function(e) {
        let id = $(this).data('id');
        $('#del_expense').val(id);
        $('#delete_expense_modal').modal("show");
    })

    //delete expense listener
    const delete_expense_form = document.getElementById("delete_expense_form");
    delete_expense_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            showLoader();
            closeModal('#delete_expense_modal');
            const id = document.getElementById('del_expense').value;
            const delURL = "{{ route('delete.expense', ':id') }}".replace(':id', id);
            const del_res = await axios.delete(delURL);
            hideLoader();
            if (del_res.status == 200 && del_res.data.status == 'success') {
                await getExpenses();
                toastr.success(del_res.data.message);
            } else if (del_res.status == 200 && del_res.data.status == 'failed') {
                toastr.error(del_res.data.message);
            }
        } catch (error) {
            hideLoader();
            console.log(error);
        }
    });
</script>
