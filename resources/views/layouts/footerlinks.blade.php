<script>
        // Show loader on page unload and before content is loaded
    window.addEventListener('beforeunload', function() {
        document.querySelector('.loader').style.display = 'flex';
    });

    // Hide loader when the page is fully loaded
    window.addEventListener('load', function() {
        document.querySelector('.loader').style.display = 'none';
    });
</script>
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nanoscroller/0.8.7/jquery.nanoscroller.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-placeholder/2.3.1/jquery.placeholder.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.appear/0.4.1/jquery.appear.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>


<!-- Vendor -->
<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- <script src="/assets/vendor/common/common.js"></script> -->
<script src="/assets/vendor/select2/js/select2.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="/assets/js/theme.js"></script>

<!-- Theme Custom -->
<script src="/assets/js/custom.js"></script>

<!-- Theme Initialization Files -->
<script src="/assets/js/theme.init.js"></script>

<!-- Examples -->
<script src="/assets/js/examples/examples.header.menu.js"></script>
<script src="/assets/js/examples/examples.dashboard.js"></script>
<script src="/assets/js/examples/examples.datatables.default.js"></script>
<script src="/assets/js/examples/examples.modals.js"></script>


