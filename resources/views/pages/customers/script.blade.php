<script>
    //customer list show
    customerList();
    async function customerList() {
        try {
            document.getElementById("customer_list").innerHTML = "";
            const URL = "{{ route('customers') }}";
            const result = await axios.get(URL);

            $('#customer_list').empty();

            result.data.forEach((customer, key) => {
                document.getElementById("customer_list").innerHTML += `<tr>
                        <td>${(key + 1) < 10 ? "0" + (key + 1) : key + 1}</td>
                        <td class="productimgname">
                            <a href="javascript:void(0);" class="product-img">
                                <img src=${customer["image"]
                        ? "{{ asset('storage/customer/') }}/" +
                        customer["image"]
                        : "{{ asset('assets/no_image.jpg') }}"
                    }  alt="product">
                            </a>
                        </td>
                        <td>${customer["name"]}</td>
                        <td>${customer["mobile"]}</td>
                        <td><a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                                data-cfemail="f4809c9b999587b4918c9599849891da979b99">${customer["email"]
                    }</a>
                        </td>
                        <td>
                            <button class="mr-3 update_customer_info" data-id="${customer["id"]}" data-name="${customer["name"]}" data-email="${customer["email"]}" data-mobile="${customer["mobile"]}" data-image="${customer["image"]}" >
                                <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="img">
                            </button>
                            <button type="button" class="mr-3 delete_customer_data" data-id="${customer["id"]}">
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

    //add new customer listener
    const add_customer_form = document.getElementById("add_customer_form");
    add_customer_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const data = new FormData(add_customer_form);

            const name = data.get("name");
            const email = data.get("email");
            const mobile = data.get("mobile");
            const image = data.get("image");
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const mobileNumberRegex = /^(\+8801|01)[1-9][0-9]{8}$/;

            if (name.length == 0) {
                toastr.info("Name is required", "POS Says:");
            } else if (email.length == 0) {
                toastr.info("Email is required", "POS Says:");
            } else if (!emailRegex.test(email)) {
                toastr.info("Invalid email format", "POS Says:");
            } else if (!mobileNumberRegex.test(mobile) && mobile) {
                toastr.info("Invalid mobile format", "POS Says:");
            } else if (image.size > 0.5 * 1024 * 1024) {
                toastr.info(
                    "You can upload a maximum of 512 KB image.",
                    "POS Says:"
                );
            } else {
                showLoader();
                hiddenModal('add_customer_modal', 'add_customer_form', 'showImage')
                const addURL = "{{ route('add.customer') }}";
                const response = await axios.post(addURL, data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                hideLoader();
                await customerList();
                if (response.status == 200 && response.data.status == 'success') {
                    toastr.success(response.data.message, "POS Says:");
                }
            }
        } catch (error) {
            console.log('Internal server error');
            console.log(error)
        }
    });

    //edit form image preview
    imagePreview('#up_image', '#showUpImage');

    // show customer value in edit form
    $(document).on('click', '.update_customer_info', function(e) {
        showModal('edit_customer_modal')
        let id, name, email, address, mobile, image;
        id = $(this).data('id');
        name = $(this).data('name');
        email = $(this).data('email');
        mobile = $(this).data('mobile');
        image = $(this).data('image');

        $('#up_id').val(id);
        $('#up_name').val(name);
        $('#up_email').val(email);
        $('#up_mobile').val(mobile);

        $("#showUpImage").attr("src", image ? `{{ asset('storage/customer/${image}') }}` :
            "{{ asset('assets/no_image.jpg') }}");
    })

    //update customer listener
    const edit_customer_form = document.getElementById("edit_customer_form");
    edit_customer_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            const data = new FormData(edit_customer_form);

            const id = data.get("id");
            const name = data.get("name");
            const email = data.get("email");
            const mobile = data.get("mobile");
            const image = data.get("image");
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const mobileNumberRegex = /^(\+8801|01)[1-9][0-9]{8}$/;

            if (name.length == 0) {
                toastr.info("Name is required", "POS Says:");
            } else if (email.length == 0) {
                toastr.info("Email is required", "POS Says:");
            } else if (!emailRegex.test(email)) {
                toastr.info("Invalid email format", "POS Says:");
            } else if (!mobileNumberRegex.test(mobile) && mobile) {
                toastr.info("Invalid mobile format", "POS Says:");
            } else if (image.size > 0.5 * 1024 * 1024) {
                toastr.info(
                    "You can upload a maximum of 512 KB image.",
                    "POS Says:"
                );
            } else {
                showLoader();
                hiddenModal('edit_customer_modal', 'edit_customer_form')
                const updateURL = "{{ route('update.customer', ':id') }}".replace(':id', id);
                const response = await axios.post(updateURL, data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                hideLoader();
                await customerList();
                if (response.status == 200 && response.data.status == 'success') {
                    toastr.success(response.data.message, "POS Says:");
                }
            }
        } catch (error) {
            console.log('Internal server error');
        }
    });

    // show customer id in delete form
    $(document).on('click', '.delete_customer_data', function(e) {
        showModal('delete_customer_modal')
        let id = $(this).data('id');
        $('#del_id').val(id);
    })

    //delete customer listener
    const delete_customer_form = document.getElementById("delete_customer_form");
    delete_customer_form.addEventListener("submit", async (e) => {
        e.preventDefault();
        try {
            showLoader();
            hiddenModal('delete_customer_modal', 'delete_customer_form')
            const id = document.getElementById('del_id').value;
            const delURL = "{{ route('delete.customer', ':id') }}".replace(':id', id);
            const del_res = await axios.delete(delURL);
            hideLoader();
            await customerList();
            if (del_res.status == 200 && del_res.data.status == 'success') {
                toastr.success(del_res.data.message, "POS Says:");
            }
        } catch (error) {
            console.log('Internal server error');
        }
    });
</script>
