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

// session expire 
let timeoutWarning = 1 * 60 * 1000; // 1 minute in milliseconds
let timeoutRedirect = 2 * 60 * 1000; // 2 minutes in milliseconds
let warningTimeout;
let redirectTimeout;
let warningShown = false;

// Function to reset the timer
function resetTimer() {
    clearTimeout(warningTimeout);
    clearTimeout(redirectTimeout);
    warningShown = false;
    warningTimeout = setTimeout(showModal, timeoutWarning); // Show warning modal
    redirectTimeout = setTimeout(expireSession, timeoutRedirect); // Expire session
}

// Function to show the warning modal
function showModal() {
    warningShown = true;
    $('#timeoutModal').show(); // Show the modal
}

// Function to expire the session
function expireSession() {
    if (!warningShown) {
        $('#timeoutModal').hide(); // Ensure modal is hidden if session expires without showing it
    }
    fetch('/logout', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(() => {
        window.location.reload(); // Reload the page to show the login screen
    });
}

// Keep the session alive
$('#continueSession').on('click', function() {
    $.post('/keep-alive', {_token: '{{ csrf_token() }}'}) // Send a request to keep session alive
        .done(() => {
            $('#timeoutModal').hide(); // Hide the modal
            resetTimer(); // Reset the timer
        });
});

// Manual logout from modal
$('#logoutSession').on('click', function() {
    fetch('/logout', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(() => {
        window.location.href = '/logout'; // Redirect to logout
    });
});

// Add event listeners for user activity to reset the timer
$(document).on('mousemove keypress click scroll', resetTimer);

// Initial call to reset the timer when the script loads
resetTimer();s

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