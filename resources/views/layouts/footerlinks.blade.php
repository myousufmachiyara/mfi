
<script>
    // Function to enable/disable elements
    function toggleElements(disable) {
        const elements = document.querySelectorAll('button, a, input[type="submit"], form');
        elements.forEach(element => {
            if (element.tagName === 'A' || element.tagName === 'BUTTON') {
                element.onclick = disable ? (e) => e.preventDefault() : null; // Prevent default action if disabled
            }
            element.disabled = disable; // Disable button or input
        });
    }

    // Show loader on page unload and before content is loaded
    window.addEventListener('beforeunload', function() {
        document.querySelector('.loader').style.display = 'flex';
        toggleElements(true); // Disable elements
    });

    // Hide loader when the page is fully loaded
    window.addEventListener('load', function() {
        document.querySelector('.loader').style.display = 'none';
        toggleElements(false); // Enable elements
    });
</script>

<!-- <script>
    // Show loader on page unload and before content is loaded
    window.addEventListener('beforeunload', function() {
        document.querySelector('.loader').style.display = 'flex';
    });

    // Hide loader when the page is fully loaded
    window.addEventListener('load', function() {
        document.querySelector('.loader').style.display = 'none';
    });
</script> -->
<!-- jQuery -->

<script src="/assets/vendor/jquery/jquery.js"></script>
<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="/assets/vendor/nanoscroller/nanoscroller.js"></script>
<script src="/assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
<script src="/assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
<script src="/assets/vendor/jquery-ui/jquery-ui.js"></script>
<script src="/assets/vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js"></script>
<script src="/assets/vendor/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="/assets/vendor/datatables/media/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.appear/0.4.1/jquery.appear.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="/assets/vendor/bootstrapv5-multiselect/js/bootstrap-multiselect.js"></script>

<!-- Vendor -->
<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- <script src="/assets/vendor/common/common.js"></script> -->
<script src="/assets/vendor/select2/js/select2.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="/assets/js/theme.js"></script>

<!-- Theme Custom -->
<script src="/assets/js/custom.js"></script>

<!-- Examples -->
<script src="/assets/js/examples/examples.header.menu.js"></script>
<script src="/assets/js/examples/examples.dashboard.js"></script>
<script src="/assets/js/examples/examples.datatables.default.js"></script>
<script src="/assets/js/examples/examples.modals.js"></script>

<!-- Theme Initialization Files -->
<script src="/assets/js/theme.init.js"></script>




