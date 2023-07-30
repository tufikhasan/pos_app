<script>
    //category list show
    categoryList();
    async function categoryList() {
        try {
            document.getElementById("category_list").innerHTML = "";
            const URL = "{{ route('categories') }}";
            const result = await axios.get(URL);
            result.data.forEach((category, key) => {
                document.getElementById("category_list").innerHTML += `<tr>
                        <td>${(key + 1) < 10 ? "0" + (key + 1) : key + 1}</td>
                        <td class="productimgname">
                            <a href="javascript:void(0);" class="product-img">
                                <img src=${category["image"]
                        ? "{{ asset('storage/category/') }}/" +
                        category["image"]
                        : "{{ asset('assets/no_image.jpg') }}"
                    }  alt="product">
                            </a>
                        </td>
                        <td>${category["name"]}</td>
                        <td>
                            <button class="mr-3 update_category_info" data-id="${category["id"]}" data-name="${category["name"]}" data-image="${category["image"]}" >
                                <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="img">
                            </button>
                            <button type="button" class="mr-3 delete_category_data" data-id="${category["id"]}">
                                <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="img">
                            </button>
                        </td>
                    </tr>`;
            });
        } catch (error) {
            console.log("Internal Server Error");
            // onclick="showModal('edit_category_modal')"
        }
    }

    //add form image preview
    imagePreview('#image', '#showImage');

    //add new category listener
    const add_category_form = document.getElementById("add_category_form");
    add_category_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const data = new FormData(add_category_form);

            const name = data.get("name");
            const image = data.get("image");

            if (name.length == 0) {
                toastr.info("Name is required", "POS Says:");
            } else if (image.size > 0.5 * 1024 * 1024) {
                toastr.info(
                    "You can upload a maximum of 512 KB image.",
                    "POS Says:"
                );
            } else {
                const addURL = "{{ route('add.category') }}";
                const response = await axios.post(addURL, data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                hiddenModal('add_category_modal', 'add_category_form', 'showImage')
                categoryList();
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

    // show category value in edit form
    $(document).on('click', '.update_category_info', function(e) {
        showModal('edit_category_modal')
        let id, name, email, address, phone;
        id = $(this).data('id');
        name = $(this).data('name');
        image = $(this).data('image');

        $('#up_id').val(id);
        $('#up_name').val(name);

        $("#showUpImage").attr("src", image ? `{{ asset('storage/category/${image}') }}` :
            "{{ asset('assets/no_image.jpg') }}");
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
                toastr.info("Name is required", "POS Says:");
            } else if (image.size > 0.5 * 1024 * 1024) {
                toastr.info(
                    "You can upload a maximum of 512 KB image.",
                    "POS Says:"
                );
            } else {
                const updateURL = "{{ route('update.category', ':id') }}".replace(':id', id);
                const response = await axios.post(updateURL, data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                hiddenModal('edit_category_modal', 'edit_category_form')
                categoryList();
                if (response.status == 200 && response.data.status == 'success') {
                    toastr.success(response.data.message, "POS Says:");
                }
            }
        } catch (error) {
            console.log('Internal server error');
        }
    });

    // show category id in delete form
    $(document).on('click', '.delete_category_data', function(e) {
        showModal('delete_category_modal')
        let id = $(this).data('id');
        $('#del_id').val(id);
    })

    //update category listener
    const delete_category_form = document.getElementById("delete_category_form");
    delete_category_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const id = document.getElementById('del_id').value;
            const delURL = "{{ route('delete.category', ':id') }}".replace(':id', id);
            const del_res = await axios.delete(delURL);
            hiddenModal('delete_category_modal', 'delete_category_form')
            categoryList();
            if (del_res.status == 200 && del_res.data.status == 'success') {
                toastr.success(del_res.data.message, "POS Says:");
            }
        } catch (error) {
            console.log('Internal server error');
        }
    });
</script>
