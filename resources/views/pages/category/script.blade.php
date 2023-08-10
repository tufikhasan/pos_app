<script>
    getCategories()
    async function getCategories() {
        const user_id = "{{ $user_id }}";
        const user_role = "{{ $user_role }}";
        try {
            let tableList = $("#categories_list");
            let tableData = $(".table");

            tableData.DataTable().destroy();
            tableList.empty();

            showLoader();


            const URL = "{{ route('categories') }}";
            const res = await axios.get(URL);
            hideLoader();

            res.data.forEach((category, key) => {
                document.getElementById('categories_list').innerHTML += `
            <tr>
                <td class='align-middle'>${key + 1 < 10 ? "0" + (key + 1) : key + 1}</td>
                <td class='align-middle'><img width="60" src=${category['image'] ? "{{ asset('upload/category') }}/" + category['image'] : "{{ asset('assets/img/no_image.jpg') }}"} /></td>
                <td class='align-middle'>${category['name']}</td>
                <td class='align-middle'>${category['user']['name']} - (${category['user']['role']})</td>
                    ${['admin', 'manager'].includes(user_role) ? 
                        `<td class='align-middle'>
                            <button class="btn btn-icon btn-sm btn-info edit_category" type="button" data-id="${category['id']}" >
                                <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                            </button>
                            ${'admin' == user_role ? 
                                `<button class="btn btn-icon btn-sm btn-danger delete_category" type="button" data-id="${category['id']}" >
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
        }
    }

    //add new category listener
    const add_category_modal = document.getElementById("add_category_modal");
    add_category_modal.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const data = new FormData(add_category_form);

            const name = data.get("name");
            const image = data.get("image");

            if (name.length == 0) {
                toastr.info("Name is required");
            } else if (image.size > 0.5 * 1024 * 1024) {
                toastr.info("You can upload a maximum of 512 KB image.");
            } else {
                showLoader();
                const addURL = "{{ route('add.category') }}";
                const response = await axios.post(addURL, data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                hideLoader();
                closeModal('#add_category_modal', 'add_category_form');
                if (response.status == 201 && response.data.status == 'success') {
                    await getCategories();
                    toastr.success(response.data.message);
                }
                if (response.status == 200 && response.data.status == 'failed') {
                    toastr.error(response.data.message);
                }
            }
        } catch (error) {
            hideLoader();
            if (error.response.status == 400) {
                toastr.error(error.response.data.message);
            }
            if (error.response.status == 403) {
                if (error.response.data.message.name) {
                    toastr.error(error.response.data.message.name);
                }
                if (error.response.data.message.image) {
                    toastr.error(error.response.data.message.image);
                }
            }
        }

    });

    async function fillCategoryEditForm(id) {
        try {
            const URL = "{{ route('single.category', ':id') }}".replace(':id', id);
            const res = await axios.get(URL);
            const image = res.data['image'];

            document.getElementById('up_id').value = res.data['id'];
            document.getElementById('up_name').value = res.data['name'];
            document.getElementById('up_category_img_preview').src = image ? "{{ asset('upload/category') }}/" +
                image :
                "{{ asset('assets/img/no_image.jpg') }}";
        } catch (error) {
            console.log("Something went wrong")
        }
    }

    // show category id in edit form
    $(document).on('click', '.edit_category', async function(e) {
        let id = $(this).data('id');
        await fillCategoryEditForm(id);
        $('#edit_category_modal').modal("show");
    })

    //update category listener
    const edit_category_form = document.getElementById("edit_category_form");
    edit_category_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const data = new FormData(edit_category_form);

            const id = data.get("id");
            const name = data.get("name");
            const image = data.get("image");

            if (name.length == 0) {
                toastr.info("Name is required");
            } else if (image.size > 0.5 * 1024 * 1024) {
                toastr.info("You can upload a maximum of 512 KB image.");
            } else {
                showLoader();
                const updateURL = "{{ route('update.category', ':id') }}".replace(':id', id);
                const response = await axios.post(updateURL, data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                hideLoader();
                closeModal('#edit_category_modal');
                if (response.status == 200 && response.data.status == 'success') {
                    await getCategories();
                    toastr.success(response.data.message);
                }
                if (response.status == 200 && response.data.status == 'failed') {
                    toastr.error(response.data.message);
                }
            }
        } catch (error) {
            hideLoader();
            if (error.response.status == 400) {
                toastr.error(error.response.data.message);
            }
            if (error.response.status == 403) {
                if (error.response.data.message.name) {
                    toastr.error(error.response.data.message.name);
                }
                if (error.response.data.message.image) {
                    toastr.error(error.response.data.message.image);
                }
            }
        }
    });

    // show category id in delete form
    $(document).on('click', '.delete_category', function(e) {
        let id = $(this).data('id');
        $('#del_category').val(id);
        $('#delete_category_modal').modal("show");
    })

    //delete category listener
    const delete_category_form = document.getElementById("delete_category_form");
    delete_category_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            showLoader();
            closeModal('#delete_category_modal');
            const id = document.getElementById('del_category').value;
            const delURL = "{{ route('delete.category', ':id') }}".replace(':id', id);
            const del_res = await axios.delete(delURL);
            hideLoader();
            if (del_res.status == 200 && del_res.data.status == 'success') {
                await getCategories();
                toastr.success(del_res.data.message);
            } else if (del_res.status == 200 && del_res.data.status == 'failed') {
                toastr.error(del_res.data.message);
            }
        } catch (error) {
            hideLoader();
            if (error.response.status == 404) {
                toastr.error(error.response.data.message);
            } else {
                console.log('Something went wrong');
            }
            console.log(error)
        }
    });
</script>
