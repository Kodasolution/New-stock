<!-- BEGIN: Vendor JS-->
<script src="{{ asset(mix('assets/vendor/libs/jquery/jquery.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/popper/popper.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/js/bootstrap.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/hammer/hammer.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/typeahead-js/typeahead.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/js/menu.js')) }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset(mix('assets/js/main.js')) }}"></script>

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
{{-- <script src="{{ asset('assets/js/forms-selects.js') }}"></script> --}}
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>
<script>
    let deleteAll = document.querySelectorAll('.deleteAll');
    deleteAll.forEach((item) => {
        item.addEventListener('click', (event) => {
            event.preventDefault();
            const def_msg = "Are you sure you want to delete this ";
            let current = event.currentTarget;
            let msg = current.dataset.msg ?
                def_msg + current.dataset.msg : "You won't be able to revert this!";
            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Successfully deleted.',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                    let fs = event.target;
                    if (fs.form !== undefined) {
                        fs.form.submit();
                    }
                    fs.parentNode.form.submit();
                }
            });
        });
    });
</script>
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
