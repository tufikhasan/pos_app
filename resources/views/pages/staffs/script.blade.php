<script>
    getStaffs()
    async function getStaffs() {
        const user_id = "{{ $user_id }}";
        const user_role = "{{ $user_role }}";
        try {
            let tableList = $("#staffs_list");
            let tableData = $(".table");

            tableData.DataTable().destroy();
            tableList.empty();

            showLoader();


            const URL = "{{ route('staffs') }}";
            const res = await axios.get(URL);
            hideLoader();

            res.data.forEach((staff, key) => {
                document.getElementById('staffs_list').innerHTML += `
            <tr>
                <td class='align-middle'>${key + 1 < 10 ? "0" + (key + 1) : key + 1}</td>
                <td class='align-middle'><img width="60" src=${staff['image'] ? "{{ asset('') }}" + staff['image'] : "{{ asset('assets/img/no_image.jpg') }}"} /></td>
                <td class='align-middle'>${staff['name']}</td>
                <td class='align-middle'>${staff['email']}</td>
                <td class='align-middle'>${staff['mobile']}</td>
                <td class='align-middle'>${staff['role']}</td>
                ${['admin', 'manager'].includes(user_role) ?
                    `<td class='align-middle'>
                        ${user_id != staff['id'] && staff['role'] != 'admin' ?
                            `<button class="btn btn-icon btn-sm btn-info edit_staff" type="button" data-id="${staff['id']}" >
                                <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                            </button>
                            ${user_role == 'admin' ?
                                `<button class="btn btn-icon btn-sm btn-danger delete_staff" type="button" data-id="${staff['id']}" >
                                    <span class="btn-inner--icon"><i class="fas fa-trash-alt"></i></span>
                                </button>` 
                            : ''}` 
                        : (staff['role'] == 'admin' ? "Shop Owner" : "It's you")}
                    </td>`
                : ""}
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
        }
    }

    //add new staff listener
    const add_staff_modal = document.getElementById("add_staff_modal");
    add_staff_modal.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const mobile = document.getElementById('mobile').value;
            const role = document.getElementById('role').value;
            const password = document.getElementById('password').value;

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const regexMobileNumber = /^(\+8801|01)\d{9}$/;

            if (0 == name.length) {
                toastr.info("Staff Name is Required");
            } else if (0 == email.length) {
                toastr.info("Staff Email is Required");
            } else if (!emailRegex.test(email)) {
                toastr.info("Invalid email format");
            } else if (mobile && !regexMobileNumber.test(mobile)) {
                toastr.info("Invalid Mobile format");
            } else if (0 == password.length) {
                toastr.info("Password is Required");
            } else {
                const data = {
                    name: name,
                    email: email,
                    mobile: mobile,
                    role: role,
                    password: password,
                }
                showLoader();
                const addURL = "{{ route('add.staff') }}";
                const response = await axios.post(addURL, data);
                hideLoader();
                await getStaffs();
                closeModal('#add_staff_modal', 'add_staff_form');
                if (response.status == 201 && response.data.status == 'success') {
                    toastr.success(response.data.message);
                } else if (response.status == 200 && response.data.status == 'failed') {
                    toastr.error(response.data.message);
                }
            }
        } catch (error) {
            hideLoader();
            if (error.response.data.message.email) {
                toastr.error(error.response.data.message.email);
            } else if (error.response.data.message.mobile) {
                toastr.error(error.response.data.message.mobile);
            } else {
                console.log('Something went wrong');
            }
        }
    });

    async function fillStaffEditForm(id) {
        try {
            const URL = "{{ route('single.staff', ':id') }}".replace(':id', id);
            const res = await axios.get(URL);
            document.getElementById('up_id').value = res.data['id'];
            document.getElementById('up_name').value = res.data['name'];
            document.getElementById('up_email').value = res.data['email'];
            document.getElementById('up_mobile').value = res.data['mobile'];

            // Set the selected option using javascript
            const options = document.querySelectorAll("#up_role option");
            options.forEach(option => {
                if (option.value === res.data['role']) {
                    option.selected = true;
                } else {
                    option.selected = false;
                }
            });
        } catch (error) {
            console.log("Something went wrong")
        }
    }

    // show staff id in edit form
    $(document).on('click', '.edit_staff', async function(e) {
        let id = $(this).data('id');
        await fillStaffEditForm(id);
        $('#edit_staff_modal').modal("show");
    })

    // update product listener
    const edit_staff_form = document.getElementById("edit_staff_form");
    edit_staff_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const id = document.getElementById('up_id').value;
            const name = document.getElementById('up_name').value;
            const email = document.getElementById('up_email').value;
            const mobile = document.getElementById('up_mobile').value;
            const role = document.getElementById('up_role').value;

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const regexMobileNumber = /^(\+8801|01)\d{9}$/;

            if (0 == name.length) {
                toastr.info("Staff Name is Required");
            } else if (0 == email.length) {
                toastr.info("Staff Email is Required");
            } else if (!emailRegex.test(email)) {
                toastr.info("Invalid email format");
            } else if (mobile && !regexMobileNumber.test(mobile)) {
                toastr.info("Invalid Mobile format");
            } else {
                const data = {
                    name: name,
                    email: email,
                    mobile: mobile,
                    role: role,
                }
                showLoader();
                const updateURL = "{{ route('update.staff', ':id') }}".replace(':id', id);
                const response = await axios.patch(updateURL, data);
                hideLoader();
                await getStaffs();
                closeModal('#edit_staff_modal');
                if (response.status == 200 && response.data.status == 'success') {
                    console.log(response.data.message)
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

    // show staff id in delete form
    $(document).on('click', '.delete_staff', function(e) {
        let id = $(this).data('id');
        $('#del_staff').val(id);
        $('#delete_staff_modal').modal("show");
    })

    //delete staff listener
    const delete_staff_form = document.getElementById("delete_staff_form");
    delete_staff_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            showLoader();
            closeModal('#delete_staff_modal');
            const id = document.getElementById('del_staff').value;
            const delURL = "{{ route('delete.staff', ':id') }}".replace(':id', id);
            const del_res = await axios.delete(delURL);
            hideLoader();
            if (del_res.status == 200 && del_res.data.status == 'success') {
                await getStaffs();
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
