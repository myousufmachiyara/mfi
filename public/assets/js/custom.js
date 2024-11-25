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

// Session expiration logic
const timeoutWarning = 0.5 * 60 * 1000; // 30 seconds in milliseconds
const timeoutRedirect = 0.7 * 60 * 1000; // 42 seconds in milliseconds
let warningTimeout;
let redirectTimeout;

// Reset session activity timer
function resetTimer() {
    // Clear any existing timeouts
    clearTimeout(warningTimeout);
    clearTimeout(redirectTimeout);

    // Set new timeouts
    warningTimeout = setTimeout(showModal, timeoutWarning); // Warning modal
    redirectTimeout = setTimeout(expireSession, timeoutRedirect); // Expire session
}       

// Show warning modal
function showModal() {
    console.log("in showModal function");
    $('#timeoutModal').show(); // Display the modal
}

// Expire session due to inactivity
function expireSession() {
    fetch('/logout-timeout', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}', // CSRF token for security
        }
    })
    .then(response => {
        if (response.ok) {
            console.log('Session expired and logged out.');
            // Check for redirection (redirect status)
            if (response.redirected) {
                window.location.href = response.url; // Redirect to the URL Laravel sends in response
            }
        } else {
            console.error('Logout timeout failed:', response.statusText);
        }
    })
    .catch(err => {
        console.error('Error with logout timeout request:', err);
    });
}

// Keep session alive after user confirms activity
$('#continueSession').on('click', function() {
    fetch('/keep-alive', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for security
        }
    })
    .then(() => {
        $('#timeoutModal').hide(); // Hide the warning modal
        resetTimer(); // Restart the timers
    })
    .catch(err => console.error('Failed to keep session alive:', err));
});

// Logout manually from the warning modal
$('#logoutSession').on('click', function() {
    fetch('/logout', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for security
        }
    }).then(() => {
        window.location.href = '/logout'; // Redirect to logout route
    }).catch(err => console.error('Manual logout failed:', err));
});

// Monitor user activity to reset the timer
$(document).on('mousemove keypress click scroll', resetTimer);

// Initialize the session activity timer when the page loads
resetTimer();

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