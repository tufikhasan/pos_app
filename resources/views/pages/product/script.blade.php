<script>
    //product list show
    productList();
    async function productList() {
        try {
            document.getElementById("product_list").innerHTML = "";
            const URL = "{{ route('products') }}";
            const result = await axios.get(URL);
            result.data.forEach((product, key) => {
                document.getElementById("product_list").innerHTML += `<tr>
                        <td>${(key + 1) < 10 ? "0" + (key + 1) : key + 1}</td>
                        <td class="productimgname">
                            <a href="javascript:void(0);" class="product-img">
                                <img src=${product["image"]
                        ? "{{ asset('storage/product/') }}/" +
                        product["image"]
                        : "{{ asset('assets/no_image.jpg') }}"
                    }  alt="product">
                            </a>
                        </td>
                        <td>${product["name"]}</td>
                        <td>${product["price"]}</td>
                        <td>${product["unit"]}</td>
                        <td>${product["brand_id"]}</td>
                        <td>${product["category_id"]}</td>
                        <td>
                            <button class="mr-3 update_product_info" data-id="${product["id"]}" data-name="${product["name"]}" data-price="${product["price"]}" data-unit="${product["unit"]}" data-brand="${product["brand_id"]}" data-category="${product["category_id"]}" data-image="${product["image"]}" >
                                <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="img">
                            </button>
                            <button type="button" class="mr-3 delete_product_data" data-id="${product["id"]}">
                                <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="img">
                            </button>
                        </td>
                    </tr>`;
            });
        } catch (error) {
            console.log("Internal Server Error");
        }
    }

    //add form image preview
    imagePreview('#image', '#showImage');

    //show brand list & category list in add form
    populateDropdownList("{{ route('brands') }}", 'brand_list');
    populateDropdownList("{{ route('categories') }}", 'category_list');

    //add new product listener
    const add_product_form = document.getElementById("add_product_form");
    add_product_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const data = new FormData(add_product_form);

            const name = data.get("name");
            const price = data.get("price");
            const unit = data.get("unit");
            const image = data.get("image");

            if (name.length == 0) {
                toastr.info("Name is required", "POS Says:");
            } else if (price.length == 0) {
                toastr.info("Price is required", "POS Says:");
            } else if (unit.length == 0) {
                toastr.info("Unit is required", "POS Says:");
            } else if (image.size > 0.5 * 1024 * 1024) {
                toastr.info(
                    "You can upload a maximum of 512 KB image.",
                    "POS Says:"
                );
            } else {
                const addURL = "{{ route('add.product') }}";
                const response = await axios.post(addURL, data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                hiddenModal('add_product_modal', 'add_product_form', 'showImage')
                productList();
                if (response.status == 200 && response.data.status == 'success') {
                    toastr.success(response.data.message, "POS Says:");
                }
            }
        } catch (error) {
            console.log('Internal server error');
        }
    });

    //edit form image preview
    imagePreview('#up_image', '#showUpImage');

    //show brand list & category list in edit form
    populateDropdownList("{{ route('brands') }}", 'up_brand_list');
    populateDropdownList("{{ route('categories') }}", 'up_category_list');

    // show product value in edit form
    $(document).on('click', '.update_product_info', function(e) {
        showModal('edit_product_modal')
        let id, name, price, unit, brand, category, image;
        id = $(this).data('id');
        name = $(this).data('name');
        price = $(this).data('price');
        unit = $(this).data('unit');
        brand = $(this).data('brand');
        category = $(this).data('category');
        image = $(this).data('image');

        // Set the selected option for "Brand"
        $("#up_brand_list option").each(function() {
            if ($(this).val() == brand) {
                $(this).attr("selected", true);
            } else {
                $(this).attr("selected", false);
            }
        });

        // Set the selected option for "Category"
        $("#up_category_list option").each(function() {
            if ($(this).val() == category) {
                $(this).attr("selected", true);
            } else {
                $(this).attr("selected", false);
            }
        });

        $('#up_id').val(id);
        $('#up_name').val(name);
        $('#up_price').val(price);
        $('#up_unit').val(unit);

        $("#showUpImage").attr("src", image ? `{{ asset('storage/product/${image}') }}` :
            "{{ asset('assets/no_image.jpg') }}");
    })

    //update product listener
    const edit_product_form = document.getElementById("edit_product_form");
    edit_product_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const data = new FormData(edit_product_form);

            const name = data.get("name");
            const price = data.get("price");
            const unit = data.get("unit");
            const image = data.get("image");

            if (name.length == 0) {
                toastr.info("Name is required", "POS Says:");
            } else if (price.length == 0) {
                toastr.info("Price is required", "POS Says:");
            } else if (unit.length == 0) {
                toastr.info("Unit is required", "POS Says:");
            } else if (image.size > 0.5 * 1024 * 1024) {
                toastr.info(
                    "You can upload a maximum of 512 KB image.",
                    "POS Says:"
                );
            } else {
                const updateURL = "{{ route('update.product', ':id') }}".replace(':id', id);
                const response = await axios.post(updateURL, data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                hiddenModal('edit_product_modal', 'edit_product_form')
                productList();
                if (response.status == 200 && response.data.status == 'success') {
                    toastr.success(response.data.message, "POS Says:");
                }
            }
        } catch (error) {
            console.log('Internal server error');
        }
    });

    // show product id in delete form
    $(document).on('click', '.delete_product_data', function(e) {
        showModal('delete_product_modal')
        let id = $(this).data('id');
        $('#del_id').val(id);
    })

    //update product listener
    const delete_product_form = document.getElementById("delete_product_form");
    delete_product_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const id = document.getElementById('del_id').value;
            const delURL = "{{ route('delete.product', ':id') }}".replace(':id', id);
            const del_res = await axios.delete(delURL);
            hiddenModal('delete_product_modal', 'delete_product_form')
            productList();
            if (del_res.status == 200 && del_res.data.status == 'success') {
                toastr.success(del_res.data.message, "POS Says:");
            }
        } catch (error) {
            console.log('Internal server error');
        }
    });
</script>
