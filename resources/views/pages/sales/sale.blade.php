@extends('layouts.backend')
@section('site_title', 'Point Of Sale')
@section('content')
    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-between align-content-center">
            <h2 class="py-2 pl-2 font-weight-600 rounded">Point Of Sale</h2>
            <button type="button" class="btn btn-primary">
                Total <span class="badge badge-light cart_count">{{ Cart::count() }}</span>
            </button>
        </div>
        <div class="col-md-6 mt-md-3">
            <div class="input-group mb-3">
                <select class="form-control" id="customer_select">
                    <option value="0">Select Customer</option>
                </select>
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#add_cus_modal"><i
                            class="ni ni-fat-add"></i> Add
                        Customer</button>
                </div>
            </div>
            @php
                $saleProducts = Cart::content();
            @endphp
            <div class="table-responsive cart_table">
                <table class="table cart_items">
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    @forelse ($saleProducts as $product)
                        <tbody>
                            <tr>
                                <td class="align-middle">{{ $product->name }}</td>
                                <td class="align-middle">
                                    <form class="update_qty_form">
                                        <div class="input-group mb-3" style="width: 90px;height:30px">
                                            <input type="hidden" value="{{ $product->rowId }}" name="up_rowId">
                                            <input type="hidden" value="{{ $product->id }}" name="up_product_id">
                                            <input class="form-control" type="number" aria-describedby="update_qty"
                                                value="{{ $product->qty }}" name="update_qty">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-sm btn-success" id="update_qty"><i
                                                        class="ni ni-check-bold"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                                <td class="align-middle">{{ $product->price }}</td>
                                <td class="align-middle">{{ $product->price * $product->qty }}</td>
                                <td class="align-middle"><button type="button"
                                        class="btn btn-sm btn-outline-danger delete_cart" data-id="{{ $product->rowId }}"><i
                                            class="ni ni-scissors"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    @empty
                        <tbody>
                            <tr>
                                <td class="align-middle text-center text-danger font-weight-600" colspan="5">No Item
                                    in
                                    cart
                                </td>
                            </tr>
                        </tbody>
                    @endforelse
                </table>
            </div>
            <div class="card bg-info">
                <div class="card-body cart_total">
                    <h4 class="text-white d-flex justify-content-between"><span>Sub
                            Total</span><span>{{ Cart::subtotal() }}</span></h4>
                    <h4 class="text-white d-flex justify-content-between">
                        <span>Tax</span><span>{{ Cart::tax() }}</span>
                    </h4>
                    <h4 class="text-white d-flex justify-content-between align-items-center">
                        <span>Discount</span>
                        <span>0</span>
                    </h4>
                    <hr class="my-1">
                    <h4 class="text-white d-flex justify-content-between"><span>Total</span><span id="total_with_dis">=
                            {{ Cart::total() }}</span></h4>

                </div>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-success" id="invoice_create">Create Invoice</button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-responsive py-4">
                <table class="table table-flush product-table" id="datatable-basic">
                    <thead class="thead-light">
                        <tr>
                            <th width="30px">Add</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>SKU</th>
                        </tr>
                    </thead>
                    <tbody id="saleProduct_list"></tbody>
                </table>
            </div>
        </div>
    </div>
    @include('pages.sales.add_customer')
@endsection
@section('script')
    <script>
        getCustomers();
        //get customers
        async function getCustomers() {
            try {
                showLoader();
                const URL = "{{ route('customers') }}";
                const res = await axios.get(URL);
                hideLoader();

                res.data.forEach((customer, key) => {
                    document.getElementById('customer_select').innerHTML +=
                        `<option value="${customer['id']}">${customer['name']}</option>`;
                });
            } catch (error) {
                hideLoader();
                console.log('Something went wrong');
            }
        }

        //add new customer listener
        const add_cus_form = document.getElementById("add_cus_form");
        add_cus_form.addEventListener("submit", async (e) => {
            e.preventDefault();
            try {
                const data = new FormData(add_cus_form);

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
                    const addURL = "{{ route('add.customer') }}";
                    const response = await axios.post(addURL, data, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    });
                    hideLoader();
                    if (response.status == 201 && response.data.status == 'success') {
                        document.getElementById('customer_select').innerHTML = "";
                        await getCustomers();
                        closeModal('#add_cus_modal', 'add_cus_form');
                        toastr.success(response.data.message);
                    }
                    if (response.status == 200 && response.data.status == 'failed') {
                        toastr.error(response.data.message);
                    }
                    if (response.status == 202 && response.data.status == 'failed') {
                        console.log(response.data.message);
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

        //get Customers
        getProducts()
        async function getProducts() {
            try {
                let tableList = $("#saleProduct_list");
                let tableData = $(".product-table");

                tableData.DataTable().destroy();
                tableList.empty();

                showLoader();


                const URL = "{{ route('products') }}";
                const res = await axios.get(URL);
                hideLoader();

                res.data.forEach((product, key) => {
                    document.getElementById('saleProduct_list').innerHTML += `
                    <tr>
                        <td class='align-middle'>
                            ${product['stock'] > 0 ?
                            `<button class="add_to_cart btn btn-sm btn-primary d-flex justify-content-center" type="submit" data-id="${product['id']}" data-name="${product['name']}" data-price="${product['price']}"><i class="ni ni-basket"></i></button>`
                            :'<span class="text-danger">Out Of Stock</span>'}   
                        </td>
                        <td class='align-middle'><img width="60" src=${product['image'] ? "{{ asset('upload/product') }}/" + product['image'] : "{{ asset('assets/img/no_image.jpg') }}"} /></td>
                        <td class='align-middle'>${product['name']}</td>
                        <td class='align-middle'>${product['brand']['name']}</td>
                        <td class='align-middle'>${product['category']['name']}</td>
                        <td class='align-middle'>${product['sku']}</td>
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

        function reloadContent() {
            $('.cart_count').load(location.href + ' .cart_count');
            $('.cart_table').load(location.href + ' .cart_items');
            $('.card').load(location.href + ' .cart_total');
        }


        // Product add to cart
        $(document).on('click', '.add_to_cart', async function(e) {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let price = $(this).data('price');
            try {
                showLoader();
                const addURL = "{{ route('add.cart') }}";
                const response = await axios.post(addURL, {
                    id: id,
                    name: name,
                    price: price,
                });
                hideLoader();
                if (response.status == 201 && response.data.status == 'success') {
                    await reloadContent();
                    toastr.success(response.data.message);
                }
                if (response.status == 200 && response.data.status == 'failed') {
                    toastr.error(response.data.message);
                }
            } catch (error) {
                hideLoader();
                if (error.response.status == 400) {
                    toastr.error(error.response.data.message);
                }
            }
        })

        // Update Product cart quantity
        $(document).on('submit', '.update_qty_form', async function(e) {
            e.preventDefault();
            let rowId = $(this).find('[name="up_rowId"]').val();
            let up_product_id = $(this).find('[name="up_product_id"]').val();
            let update_qty = $(this).find('[name="update_qty"]').val();

            try {
                if (update_qty <= 0) {
                    toastr.error("Enter a Valid Quantity");
                } else {
                    showLoader();
                    const removeURL = "{{ route('update.cart', ':rowId') }}".replace(':rowId', rowId);
                    const response = await axios.patch(removeURL, {
                        up_product_id: up_product_id,
                        update_qty: update_qty
                    });
                    hideLoader();
                    if (response.status == 200 && response.data.status == 'success') {
                        await reloadContent();
                        toastr.success(response.data.message);
                    }
                    if (response.status == 200 && response.data.status == 'failed') {
                        await reloadContent();
                        toastr.error(response.data.message);
                    }
                }

            } catch (error) {
                hideLoader();
                if (error.response.status == 400) {
                    toastr.error(error.response.data.message);
                }
            }

        })

        // Product remove from cart
        $(document).on('click', '.delete_cart', async function(e) {
            let rowId = $(this).data('id');
            try {
                showLoader();
                const removeURL = "{{ route('remove.cart', ':rowId') }}".replace(':rowId', rowId);
                const response = await axios.delete(removeURL);
                hideLoader();
                if (response.status == 200 && response.data.status == 'success') {
                    await reloadContent();
                    toastr.success(response.data.message);
                }
            } catch (error) {
                hideLoader();
                if (error.response.status == 400) {
                    toastr.error(error.response.data.message);
                }
            }

        })


        //invoice create
        const invoice_create = document.getElementById("invoice_create");
        invoice_create.addEventListener("click", async () => {
            try {
                const customer_id = document.getElementById("customer_select").value;
                if (customer_id == 0) {
                    toastr.error("Please Select a Customer");
                } else {
                    showLoader();
                    const addURL = "{{ route('create.invoice') }}";
                    const response = await axios.post(addURL, {
                        customer_id: customer_id
                    });
                    hideLoader();
                    if (response.status == 201 && response.data.status == 'success') {
                        reloadContent()
                        toastr.success(response.data.message);
                        // location.href = "{{ route('invoice.details', ':id') }}".replace(':id', response.data.id);
                        // Open a new tab with the generated URL
                        window.open("{{ route('invoice.details', ':id') }}".replace(':id', response.data.id),
                            '_blank');
                    }
                    if (response.status == 200 && response.data.status == 'failed') {
                        toastr.error(response.data.message);
                    }
                }
            } catch (error) {
                hideLoader();
                console.log("Something went Wrong")
            }

        });
    </script>
@endsection
