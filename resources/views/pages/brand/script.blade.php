<script>
    //brand list show
    brandList();
    async function brandList() {
        try {
            document.getElementById("brand_list").innerHTML = "";
            const URL = "{{ route('brands') }}";
            const result = await axios.get(URL);
            result.data.forEach((brand, key) => {
                document.getElementById("brand_list").innerHTML += `<tr>
                        <td>${(key + 1) < 10 ? "0" + (key + 1) : key + 1}</td>
                        <td class="productimgname">
                            <a href="javascript:void(0);" class="product-img">
                                <img src=${brand["image"]
                        ? "{{ asset('storage/brand/') }}/" +
                        brand["image"]
                        : "{{ asset('assets/no_image.jpg') }}"
                    }  alt="product">
                            </a>
                        </td>
                        <td>${brand["name"]}</td>
                        <td>
                            <button class="mr-3 update_brand_info" data-id="${brand["id"]}" data-name="${brand["name"]}" data-image="${brand["image"]}" >
                                <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="img">
                            </button>
                            <button type="button" class="mr-3 delete_brand_data" data-id="${brand["id"]}">
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

    //add new brand listener
    const add_brand_form = document.getElementById("add_brand_form");
    add_brand_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const data = new FormData(add_brand_form);

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
                const addURL = "{{ route('add.brand') }}";
                const response = await axios.post(addURL, data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                hiddenModal('add_brand_modal', 'add_brand_form', 'showImage')
                brandList();
                if (response.status == 200 && response.data.status == 'success') {
                    toastr.success(response.data.message, "POS Says:");
                }
            }
        } catch (error) {
            // console.log('Internal server error');
            console.log(error)
        }
    });

    //edit form image preview
    imagePreview('#up_image', '#showUpImage');

    // show brand value in edit form
    $(document).on('click', '.update_brand_info', function(e) {
        showModal('edit_brand_modal')
        let id, name, email, address, phone;
        id = $(this).data('id');
        name = $(this).data('name');
        image = $(this).data('image');

        $('#up_id').val(id);
        $('#up_name').val(name);

        $("#showUpImage").attr("src", image ? `{{ asset('storage/brand/${image}') }}` :
            "{{ asset('assets/no_image.jpg') }}");
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
                toastr.info("Name is required", "POS Says:");
            } else if (image.size > 0.5 * 1024 * 1024) {
                toastr.info(
                    "You can upload a maximum of 512 KB image.",
                    "POS Says:"
                );
            } else {
                const updateURL = "{{ route('update.brand', ':id') }}".replace(':id', id);
                const response = await axios.post(updateURL, data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                hiddenModal('edit_brand_modal', 'edit_brand_form')
                brandList();
                if (response.status == 200 && response.data.status == 'success') {
                    toastr.success(response.data.message, "POS Says:");
                }
            }
        } catch (error) {
            console.log('Internal server error');
        }
    });

    // show brand id in delete form
    $(document).on('click', '.delete_brand_data', function(e) {
        showModal('delete_brand_modal')
        let id = $(this).data('id');
        $('#del_id').val(id);
    })

    //update brand listener
    const delete_brand_form = document.getElementById("delete_brand_form");
    delete_brand_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const id = document.getElementById('del_id').value;
            const delURL = "{{ route('delete.brand', ':id') }}".replace(':id', id);
            const del_res = await axios.delete(delURL);
            hiddenModal('delete_brand_modal', 'delete_brand_form')
            brandList();
            if (del_res.status == 200 && del_res.data.status == 'success') {
                toastr.success(del_res.data.message, "POS Says:");
            }
        } catch (error) {
            console.log('Internal server error');
        }
    });
</script>
