<script>
    getProducts()
    async function getProducts() {
        const user_id = "{{ $user_id }}";
        const user_role = "{{ $user_role }}";
        try {
            let tableList = $("#products_list");
            let tableData = $(".table");

            tableData.DataTable().destroy();
            tableList.empty();

            showLoader();


            const URL = "{{ route('products') }}";
            const res = await axios.get(URL);
            hideLoader();

            res.data.forEach((product, key) => {
                document.getElementById('products_list').innerHTML += `
            <tr>
                <td class='align-middle'>${key + 1 < 10 ? "0" + (key + 1) : key + 1}</td>
                <td class='align-middle'>${product['sku']}</td>
                <td class='align-middle'><img width="60" src=${product['image'] ? "{{ asset('upload/product') }}/" + product['image'] : "{{ asset('assets/img/no_image.jpg') }}"} /></td>
                <td class='align-middle'>${product['name']}</td>
                <td class='align-middle'>${product['brand']['name']}</td>
                <td class='align-middle'>${product['category']['name']}</td>
                <td class='align-middle'>${product['price']}</td>
                <td class='align-middle'>${product['unit']}</td>
                <td class='align-middle'>${product['stock']}</td>
                <td class='align-middle'>${product['user']['name']} - (${product['user']['role']})</td>
                    ${['admin', 'manager'].includes(user_role) ? 
                        `<td class='align-middle'>
                            <button class="btn btn-icon btn-sm btn-info edit_product" type="button" data-id="${product['id']}" >
                                <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                            </button>
                            ${'admin' == user_role ? 
                                `<button class="btn btn-icon btn-sm btn-danger delete_product" type="button" data-id="${product['id']}" >
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

    //add new product listener
    const add_product_modal = document.getElementById("add_product_modal");
    add_product_modal.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const data = new FormData(add_product_form);

            const name = data.get("name");
            const email = data.get("email");
            const mobile = data.get("mobile");
            const image = data.get("image");
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const mobileNumberRegex = /^(\+8801|01)[1-9][0-9]{8}$/;

            if (name.length == 0) {
                toastr.info("Name is required");
            } else if (email.length == 0) {
                toastr.info("Email is required");
            } else if (!emailRegex.test(email)) {
                toastr.info("Invalid email format");
            } else if (!mobileNumberRegex.test(mobile) && mobile) {
                toastr.info("Invalid mobile format");
            } else if (image.size > 0.5 * 1024 * 1024) {
                toastr.info("You can upload a maximum of 512 KB image.");
            } else {
                showLoader();
                const addURL = "{{ route('add.product') }}";
                const response = await axios.post(addURL, data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                hideLoader();
                closeModal('#add_product_modal', 'add_product_form');
                if (response.status == 201 && response.data.status == 'success') {
                    await getProducts();
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
                if (error.response.data.message.email) {
                    toastr.error(error.response.data.message.email);
                }
                if (error.response.data.message.mobile) {
                    toastr.error(error.response.data.message.mobile);
                }
            }
        }

    });

    async function fillproductEditForm(id) {
        try {
            const URL = "{{ route('single.product', ':id') }}".replace(':id', id);
            const res = await axios.get(URL);
            const image = res.data['image'];

            document.getElementById('up_id').value = res.data['id'];
            document.getElementById('up_name').value = res.data['name'];
            document.getElementById('up_email').value = res.data['email'];
            document.getElementById('up_mobile').value = res.data['mobile'];
            document.getElementById('up_cus_img_preview').src = image ? "{{ asset('upload/product') }}/" + image :
                "{{ asset('assets/img/no_image.jpg') }}";
        } catch (error) {
            console.log("Something went wrong")
        }
    }

    // show product id in edit form
    $(document).on('click', '.edit_product', async function(e) {
        let id = $(this).data('id');
        await fillproductEditForm(id);
        $('#edit_product_modal').modal("show");
    })

    //update product listener
    const edit_product_form = document.getElementById("edit_product_form");
    edit_product_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const data = new FormData(edit_product_form);

            const id = data.get("id");
            const name = data.get("name");
            const email = data.get("email");
            const mobile = data.get("mobile");
            const image = data.get("image");
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const mobileNumberRegex = /^(\+8801|01)[1-9][0-9]{8}$/;

            if (name.length == 0) {
                toastr.info("Name is required");
            } else if (email.length == 0) {
                toastr.info("Email is required");
            } else if (!emailRegex.test(email)) {
                toastr.info("Invalid email format");
            } else if (!mobileNumberRegex.test(mobile) && mobile) {
                toastr.info("Invalid mobile format");
            } else if (image.size > 0.5 * 1024 * 1024) {
                toastr.info("You can upload a maximum of 512 KB image.");
            } else {
                showLoader();
                const updateURL = "{{ route('update.product', ':id') }}".replace(':id', id);
                const response = await axios.post(updateURL, data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                hideLoader();
                closeModal('#edit_product_modal');
                if (response.status == 200 && response.data.status == 'success') {
                    await getProducts();
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
                if (error.response.data.message.email) {
                    toastr.error(error.response.data.message.email);
                }
                if (error.response.data.message.mobile) {
                    toastr.error(error.response.data.message.mobile);
                }
                if (error.response.data.message.image) {
                    toastr.error(error.response.data.message.image);
                }
            }
        }
    });

    // show product id in delete form
    $(document).on('click', '.delete_product', function(e) {
        let id = $(this).data('id');
        $('#del_product').val(id);
        $('#delete_product_modal').modal("show");
    })

    //delete product listener
    const delete_product_form = document.getElementById("delete_product_form");
    delete_product_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            showLoader();
            closeModal('#delete_product_modal');
            const id = document.getElementById('del_product').value;
            const delURL = "{{ route('delete.product', ':id') }}".replace(':id', id);
            const del_res = await axios.delete(delURL);
            hideLoader();
            if (del_res.status == 200 && del_res.data.status == 'success') {
                await getProducts();
                toastr.success(del_res.data.message);
            } else if (del_res.status == 200 && del_res.data.status == 'failed') {
                toastr.error(del_res.data.message);
            }
        } catch (error) {
            hideLoader();
            console.log('Something went wrong');
        }
    });
</script>
