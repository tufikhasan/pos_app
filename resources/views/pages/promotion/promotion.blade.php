@extends('layouts.backend')
@section('site_title', 'Promotional Mail')
@section('content')
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0">Send Mail All Customers</h3>
                    <p class="text-sm mb-0">
                        This is an exmaple of datatable using the well known datatables.
                    </p>
                </div>
                <div class="card-body">
                    <form id="promotion_mail">
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" class="form-control" id="subject">
                        </div>
                        <div class="form-group">
                            <label for="message">Message Body</label>
                            <textarea class="form-control" id="message" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Mail</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        const form = document.getElementById('promotion_mail');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            try {
                const subject = document.getElementById('subject').value;
                const message = document.getElementById('message').value;
                showLoader();
                const URL = "{{ route('promotion.mail') }}";
                const response = await axios.post(URL, {
                    subject: subject,
                    message: message
                });
                hideLoader();
                if (response.status == 200 && response.data.status == 'success') {
                    form.reset();
                    toastr.success(response.data.message);
                }
                if (response.status == 200 && response.data.status == 'failed') {
                    toastr.error(response.data.message);
                }
            } catch (error) {
                hideLoader();
                console.log("Something Went Wrong");
            }
        });
    </script>
@endsection
