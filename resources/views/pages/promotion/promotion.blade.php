@extends('layouts.backend')
@section('site_title', 'Promotional Mail')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Promotional Mail</h4>
                <h6>Send mail all customers</h6>
            </div>
        </div>

        <div class="md:grid md:grid-cols-2 gap-x-6">
            <div>
                <div class="card">
                    <div class="card-body">
                        <form id="promotion_form">
                            <div class="form-group">
                                <label>subject</label>
                                <input type="text" class="form-control" id="subject">
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea id="message" cols="30" rows="20" class="form-control"></textarea>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const promotion_form = document.getElementById('promotion_form');
        promotion_form.addEventListener('submit', async (e) => {
            e.preventDefault();
            try {
                const subject = document.getElementById('subject').value;
                const message = document.getElementById('message').value;

                if (0 == subject.length) {
                    toastr.info("Subject is Required", "POS Says:");
                } else if (0 == message.length) {
                    toastr.info("Message is Required", "POS Says:");
                } else {
                    showLoader();
                    const URL = "{{ route('promotion.mail') }}";
                    const res = await axios.post(URL, {
                        subject: subject,
                        message: message
                    });
                    hideLoader();

                    if (200 == res.status && 'success' == res.data.status) {
                        promotion_form.reset();
                        toastr.success(res.data.message, "POS Says:");
                    } else if (200 == res.status && 'failed' == res.data.status) {
                        promotion_form.reset();
                        toastr.info(res.data.message, "POS Says:");
                    }
                }

            } catch (error) {
                console.log('Something went Wrong');
            }

        })
    </script>
@endsection
