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

    //show brand list & category list in add form
    populateDropdownList("{{ route('brands') }}", 'brand_list');
    populateDropdownList("{{ route('categories') }}", 'category_list');

    //add new product listener
    const add_product_modal = document.getElementById("add_product_modal");
    add_product_modal.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const data = new FormData(add_product_form);

            const name = data.get("name");
            const sku = data.get("sku");
            const unit = data.get("unit");
            const stock = data.get("stock");
            const price = data.get("price");
            const image = data.get("image");
            const brand = data.get("brand_id");
            const category = data.get("category_id");

            if (name.length == 0) {
                toastr.info("Name is required");
            } else if (sku.length == 0) {
                toastr.info("SKU is required");
            } else if (unit.length == 0) {
                toastr.info("Unit is required");
            } else if (stock.length == 0) {
                toastr.info("Stock is required");
            } else if (price.length == 0) {
                toastr.info("Price is required");
            } else if (brand == 0) {
                toastr.info("Select Brand if not Exists then create first");
            } else if (category == 0) {
                toastr.info("Select Category if not Exists then create first");
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
                if (response.status == 201 && response.data.status == 'success') {
                    await getProducts();
                    document.getElementById('pro_img_preview').src =
                        "{{ asset('assets/img/no_image.jpg') }}";
                    closeModal('#add_product_modal', 'add_product_form');
                    toastr.success(response.data.message);
                }
                if (response.status == 200 && response.data.status == 'failed') {
                    toastr.error(response.data.message);
                }
            }
        } catch (error) {
            hideLoader();
            console.log(error)
            if (error.response.status == 400) {
                toastr.error(error.response.data.message);
            }
            if (error.response.status == 403) {
                console.log("something went wrong");
            }
        }

    });

    //show brand list & category list in update form
    populateDropdownList("{{ route('brands') }}", 'up_brand_list');
    populateDropdownList("{{ route('categories') }}", 'up_category_list');

    async function fillProductEditForm(id) {
        try {
            const URL = "{{ route('single.product', ':id') }}".replace(':id', id);
            const res = await axios.get(URL);
            const image = res.data['image'];

            document.getElementById('up_id').value = res.data['id'];
            document.getElementById('up_name').value = res.data['name'];
            document.getElementById('up_sku').value = res.data['sku'];
            document.getElementById('up_unit').value = res.data['unit'];
            document.getElementById('up_stock').value = res.data['stock'];
            document.getElementById('up_price').value = res.data['price'];
            document.getElementById('up_pro_img_preview').src = image ? "{{ asset('upload/product') }}/" + image :
                "{{ asset('assets/img/no_image.jpg') }}";

            // Set the selected option for "Brand"
            $("#up_brand_list option").each(function() {
                if ($(this).val() == res.data['brand_id']) {
                    $(this).attr("selected", true);
                } else {
                    $(this).attr("selected", false);
                }
            });

            // Set the selected option for "Category"
            $("#up_category_list option").each(function() {
                if ($(this).val() == res.data['category_id']) {
                    $(this).attr("selected", true);
                } else {
                    $(this).attr("selected", false);
                }
            });
        } catch (error) {
            console.log("Something went wrong")
        }
    }

    // show product id in edit form
    $(document).on('click', '.edit_product', async function(e) {
        let id = $(this).data('id');
        showLoader();
        await fillProductEditForm(id);
        hideLoader();
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
            const sku = data.get("sku");
            const unit = data.get("unit");
            const stock = data.get("stock");
            const price = data.get("price");
            const brand = data.get("up_brand_list");
            const category = data.get("up_category_list");
            const image = data.get("image");

            if (name.length == 0) {
                toastr.info("Name is required");
            } else if (sku.length == 0) {
                toastr.info("SKU is required");
            } else if (unit.length == 0) {
                toastr.info("Unit is required");
            } else if (stock.length == 0) {
                toastr.info("Stock is required");
            } else if (price.length == 0) {
                toastr.info("Price is required");
            } else if (brand == 0) {
                toastr.info("Select Brand if not Exists then create first");
            } else if (category == 0) {
                toastr.info("Select Category if not Exists then create first");
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
                if (response.status == 200 && response.data.status == 'success') {
                    await getProducts();
                    closeModal('#edit_product_modal');
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
                console.log("something went wrong");
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
