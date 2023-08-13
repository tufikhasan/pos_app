<script>
    getBrands()
    async function getBrands() {
        const user_id = "{{ $user_id }}";
        const user_role = "{{ $user_role }}";
        try {
            let tableList = $("#brands_list");
            let tableData = $(".table");

            tableData.DataTable().destroy();
            tableList.empty();

            showLoader();


            const URL = "{{ route('brands') }}";
            const res = await axios.get(URL);
            hideLoader();

            res.data.forEach((brand, key) => {
                document.getElementById('brands_list').innerHTML += `
            <tr>
                <td class='align-middle'>${key + 1 < 10 ? "0" + (key + 1) : key + 1}</td>
                <td class='align-middle'><img width="60" src=${brand['image'] ? "{{ asset('upload/brand') }}/" + brand['image'] : "{{ asset('assets/img/no_image.jpg') }}"} /></td>
                <td class='align-middle'>${brand['name']}</td>
                <td class='align-middle'>${brand['user']['name']} - (${brand['user']['role']})</td>
                    ${['admin', 'manager'].includes(user_role) ? 
                        `<td class='align-middle'>
                            <button class="btn btn-icon btn-sm btn-info edit_brand" type="button" data-id="${brand['id']}" >
                                <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                            </button>
                            ${'admin' == user_role ? 
                                `<button class="btn btn-icon btn-sm btn-danger delete_brand" type="button" data-id="${brand['id']}" >
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

    //add new brand listener
    const add_brand_modal = document.getElementById("add_brand_modal");
    add_brand_modal.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const data = new FormData(add_brand_form);

            const name = data.get("name");
            const image = data.get("image");

            if (name.length == 0) {
                toastr.info("Name is required");
            } else if (image.size > 0.5 * 1024 * 1024) {
                toastr.info("You can upload a maximum of 512 KB image.");
            } else {
                showLoader();
                const addURL = "{{ route('add.brand') }}";
                const response = await axios.post(addURL, data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                hideLoader();
                if (response.status == 201 && response.data.status == 'success') {
                    await getBrands();
                    closeModal('#add_brand_modal', 'add_brand_form');
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

    async function fillBrandEditForm(id) {
        try {
            const URL = "{{ route('single.brand', ':id') }}".replace(':id', id);
            const res = await axios.get(URL);
            const image = res.data['image'];

            document.getElementById('up_id').value = res.data['id'];
            document.getElementById('up_name').value = res.data['name'];
            document.getElementById('up_brand_img_preview').src = image ? "{{ asset('upload/brand') }}/" + image :
                "{{ asset('assets/img/no_image.jpg') }}";
        } catch (error) {
            console.log("Something went wrong")
        }
    }

    // show brand id in edit form
    $(document).on('click', '.edit_brand', async function(e) {
        let id = $(this).data('id');
        await fillBrandEditForm(id);
        $('#edit_brand_modal').modal("show");
    })

    //update brand listener
    const edit_brand_form = document.getElementById("edit_brand_form");
    edit_brand_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const data = new FormData(edit_brand_form);

            const id = data.get("id");
            const name = data.get("name");
            const image = data.get("image");

            if (name.length == 0) {
                toastr.info("Name is required");
            } else if (image.size > 0.5 * 1024 * 1024) {
                toastr.info("You can upload a maximum of 512 KB image.");
            } else {
                showLoader();
                const updateURL = "{{ route('update.brand', ':id') }}".replace(':id', id);
                const response = await axios.post(updateURL, data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                hideLoader();
                if (response.status == 200 && response.data.status == 'success') {
                    await getBrands();
                    closeModal('#edit_brand_modal');
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

    // show brand id in delete form
    $(document).on('click', '.delete_brand', function(e) {
        let id = $(this).data('id');
        $('#del_brand').val(id);
        $('#delete_brand_modal').modal("show");
    })

    //delete brand listener
    const delete_brand_form = document.getElementById("delete_brand_form");
    delete_brand_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            showLoader();
            closeModal('#delete_brand_modal');
            const id = document.getElementById('del_brand').value;
            const delURL = "{{ route('delete.brand', ':id') }}".replace(':id', id);
            const del_res = await axios.delete(delURL);
            hideLoader();
            if (del_res.status == 200 && del_res.data.status == 'success') {
                await getBrands();
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
