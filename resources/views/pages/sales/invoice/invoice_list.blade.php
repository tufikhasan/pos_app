@php
    $user_role = request()->header('role');
@endphp
@extends('layouts.backend')
@section('site_title', 'Sales Invoice List')
@section('content')
    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0">Sales Invoice</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th>SL NO:</th>
                                    <th>Customer</th>
                                    <th>Total QTY</th>
                                    <th>Sub Total</th>
                                    <th>Tax</th>
                                    <th>Grand Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="invoice_list">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.sales.invoice.delete')
@endsection
@section('script')
    <script>
        const getInvoices = async () => {
            const user_role = "{{ $user_role }}";
            try {
                let tableList = $("#invoice_list");
                let tableData = $(".table");

                tableData.DataTable().destroy();
                tableList.empty();

                showLoader();


                const URL = "{{ route('all.invoice') }}";
                const res = await axios.get(URL);
                hideLoader();

                res.data.forEach((invoice, key) => {
                    document.getElementById('invoice_list').innerHTML += `
                    <tr>
                        <td class='align-middle'>${key + 1 < 10 ? "0" + (key + 1) : key + 1}</td>
                        <td class='align-middle'>${invoice['customer']['name']}</td>
                        <td class='align-middle'>${invoice['total_qty']}</td>
                        <td class='align-middle'>${invoice['sub_total']}</td>
                        <td class='align-middle'>${invoice['tax']}</td>
                        <td class='align-middle'>${invoice['total']}</td>
                        <td class='align-middle'>
                            <a class="btn btn-icon btn-sm btn-info" target="_blank" title="Preview" href=${"{{ route('invoice.details', ':id') }}".replace(':id',invoice['id'])} >
                                <span class="btn-inner--icon"><i class="fas fa-eye"></i></span>
                            </a>
                            ${['admin'].includes(user_role) ? 
                                `<button class="btn btn-icon btn-sm btn-danger delete_invoice" type="button" data-id="${invoice['id']}" >
                                    <span class="btn-inner--icon"><i class="fas fa-trash-alt"></i></span>
                                </button>` 
                            : '' } 
                        </td>
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
                console.log(error)
            }
        };
        getInvoices();

        // show product id in delete form
        $(document).on('click', '.delete_invoice', function(e) {
            let id = $(this).data('id');
            $('#del_invoice').val(id);
            $('#delete_invoice_modal').modal("show");
        })

        //delete product listener
        const delete_invoice_form = document.getElementById("delete_invoice_form");
        delete_invoice_form.addEventListener("submit", async (e) => {
            e.preventDefault();
            try {
                showLoader();
                closeModal('#delete_invoice_modal');
                const id = document.getElementById('del_invoice').value;
                const delURL = "{{ route('delete.invoice', ':id') }}".replace(':id', id);
                const del_res = await axios.delete(delURL);
                hideLoader();
                if (del_res.status == 200 && del_res.data.status == 'success') {
                    await getInvoices();
                    toastr.success(del_res.data.message);
                } else if (del_res.status == 200 && del_res.data.status == 'failed') {
                    toastr.error(del_res.data.message);
                }
            } catch (error) {
                hideLoader();
                console.log('Something went wrong');
                console.log(error)
            }
        });
    </script>
@endsection
