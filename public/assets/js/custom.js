/* Add here all your JS customizations */

document.getElementsByClassName('cust-textarea')[0].addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.stopPropagation(); // Prevents the Enter key from propagating to other elements
    }
});
