/* Add here all your JS customizations */

window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        // The page was loaded from the cache
        window.location.reload();
    }
});

$(window).on('load', function() {
    // Hide the loader once the page is fully loaded
    $('#loader').addClass('hidden');
});


document.querySelectorAll('.cust-textarea').forEach(function(textarea) {
    textarea.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.stopPropagation(); // Prevents the Enter key from propagating to other elements
        }
    });
});


function convertCurrencyToWords(number) {
    const Thousand = 1000;
    const Million = Thousand * Thousand;
    const Billion = Thousand * Million;
    const Trillion = Thousand * Billion;

    if (number === 0) return "Zero Rupees Only";
    
    const isNegative = number < 0;
    number = Math.abs(number);

    let result = "";

    // Trillions
    if (number >= Trillion) {
        result += convertDigitGroup(Math.floor(number / Trillion)) + " Trillion ";
        number %= Trillion;
    }

    // Billions
    if (number >= Billion) {
        result += convertDigitGroup(Math.floor(number / Billion)) + " Billion ";
        number %= Billion;
    }

    // Millions
    if (number >= Million) {
        result += convertDigitGroup(Math.floor(number / Million)) + " Million ";
        number %= Million;
    }

    // Thousands
    if (number >= Thousand) {
        result += convertDigitGroup(Math.floor(number / Thousand)) + " Thousand ";
        number %= Thousand;
    }

    // Hundreds and below
    if (number > 0) {
        result += convertDigitGroup(number);
    }

    result = result.trim() + " Rupees Only";
    
    // Handle negative case
    return isNegative ? "Negative " + result : result;
}

function convertDigitGroup(number) {
    const hundreds = Math.floor(number / 100);
    const remainder = number % 100;
    let result = "";

    if (number === 1) {
        return "One";
    }
    if (number === 2) {
        return "Two";
    }
    if (number === 3) {
        return "Three";
    }
    if (number === 4) {
        return "Four";
    }
    if (number === 5) {
        return "Five";
    }
    if (number === 6) {
        return "Six";
    }
    if (number === 7) {
        return "Seven";
    }
    if (number === 8) {
        return "Eight";
    }
    if (number === 9) {
        return "Nine";
    }
    
    if (hundreds > 0) {
        result += convertSingleDigit(hundreds) + " Hundred ";
    }

    if (remainder > 0) {
        if (remainder < 20) {
            result += convertTens(remainder);
        } else {
            result += convertTens(Math.floor(remainder / 10) * 10);
            if (remainder % 10 > 0) {
                result += "-" + convertSingleDigit(remainder % 10);
            }
        }
    }

    return result.trim();
}

function convertSingleDigit(digit) {
    const digits = {
        0: "",
        1: "One",
        2: "Two",
        3: "Three",
        4: "Four",
        5: "Five",
        6: "Six",
        7: "Seven",
        8: "Eight",
        9: "Nine"
    };

    return digits[digit];
}

function convertTens(number) {
    const tens = {
        10: "Ten",
        11: "Eleven",
        12: "Twelve",
        13: "Thirteen",
        14: "Fourteen",
        15: "Fifteen",
        16: "Sixteen",
        17: "Seventeen",
        18: "Eighteen",
        19: "Nineteen",
        20: "Twenty",
        30: "Thirty",
        40: "Forty",
        50: "Fifty",
        60: "Sixty",
        70: "Seventy",
        80: "Eighty",
        90: "Ninety"
    };

    return tens[number] || "";
}

// session maintain

let timeoutWarning = 1 * 60 * 1000; // 1 minute in milliseconds
let timeoutRedirect = 2 * 60 * 1000; // 2 minutes in milliseconds
let warningTimeout;
let warningShown = false;

function resetTimer() {
    clearTimeout(warningTimeout);
    warningShown = false;
    warningTimeout = setTimeout(showModal, timeoutWarning);
}

function showModal() {
    warningShown = true;
    $('#timeoutModal').show(); // Show the modal
}

// Continue session event
$('#continueSession').on('click', function() {
    $.post('/keep-alive', {_token: '{{ csrf_token() }}'}); // Keep session alive
    $('#timeoutModal').hide(); // Hide the modal
    resetTimer(); // Reset the timer
});

// Logout event
$('#logoutSession').on('click', function() {
    window.location.href = '/logout'; // Redirect to logout or any desired action
});

$(document).on('mousemove keypress click scroll', resetTimer);
resetTimer();

var table = $('#cust-datatable-default').DataTable(); // Retrieve the existing instance

$('#columnSearch').on('keyup change', function() {
    var columnIndex = $('#columnSelect').val(); // Get selected column index
    table.column(columnIndex).search(this.value).draw(); // Apply search and redraw
});

$('#changePasswordForm').on('submit', function(e){
    e.preventDefault();
    var currentPassword=$('#current_passowrd').val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'GET',
        url: '/validate-user-password/',
        data: {
            'password':currentPassword,
        },
        success: function(response){
            console.log(response);
            if(response==1){
                var form = document.getElementById('changePasswordForm');
                form.submit();
            }
            else{
                alert("Current Password is not Correct")
            }
        },
        error: function(){
            alert("error");
        }
    });
});	