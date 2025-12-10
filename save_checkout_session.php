<?php
session_start();
header('Content-Type: application/json');

// Check if this is a checkout data save request
if (isset($_POST['save_checkout_data'])) {
    
    // Validate required fields
    if (empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['firstName']) || 
        empty($_POST['lastName']) || empty($_POST['address']) || empty($_POST['city']) || 
        empty($_POST['state']) || empty($_POST['zip']) || empty($_POST['shippingMethod']) || 
        empty($_POST['paymentMethod'])) {
        
        echo json_encode([
            'success' => false,
            'message' => 'Please fill in all required fields'
        ]);
        exit;
    }
    
    // Save shipping information to session
    $_SESSION['checkout_email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $_SESSION['checkout_phone'] = htmlspecialchars($_POST['phone']);
    $_SESSION['checkout_firstName'] = htmlspecialchars($_POST['firstName']);
    $_SESSION['checkout_lastName'] = htmlspecialchars($_POST['lastName']);
    $_SESSION['checkout_address'] = htmlspecialchars($_POST['address']);
    $_SESSION['checkout_apartment'] = htmlspecialchars($_POST['apartment'] ?? '');
    $_SESSION['checkout_city'] = htmlspecialchars($_POST['city']);
    $_SESSION['checkout_state'] = htmlspecialchars($_POST['state']);
    $_SESSION['checkout_zip'] = htmlspecialchars($_POST['zip']);
    
    // Save shipping method
    $_SESSION['shipping_method'] = htmlspecialchars($_POST['shippingMethod']);
    
    // Calculate shipping cost
    $shippingCost = 0;
    switch ($_POST['shippingMethod']) {
        case 'express':
            $shippingCost = 15.00;
            break;
        case 'overnight':
            $shippingCost = 25.00;
            break;
        case 'standard':
        default:
            $shippingCost = 0;
            break;
    }
    $_SESSION['shipping_cost'] = $shippingCost;
    
    // Save payment method
    $_SESSION['payment_method'] = htmlspecialchars($_POST['paymentMethod']);
    
    // Save payment details based on method
    if ($_POST['paymentMethod'] === 'credit_card') {
        if (empty($_POST['cardNumber']) || empty($_POST['nameOnCard']) || 
            empty($_POST['expiryDate']) || empty($_POST['cvv'])) {
            
            echo json_encode([
                'success' => false,
                'message' => 'Please fill in all credit card details'
            ]);
            exit;
        }
        
        // Save credit card info (last 4 digits only for security)
        $cardNumber = preg_replace('/\s+/', '', $_POST['cardNumber']);
        $_SESSION['card_last4'] = substr($cardNumber, -4);
        $_SESSION['card_type'] = getCardType($cardNumber);
        $_SESSION['name_on_card'] = htmlspecialchars($_POST['nameOnCard']);
        $_SESSION['expiry_date'] = htmlspecialchars($_POST['expiryDate']);
        
        // Clear PayPal data
        unset($_SESSION['paypal_email']);
        
    } else if ($_POST['paymentMethod'] === 'paypal') {
        if (empty($_POST['paypalEmail'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Please enter your PayPal email address'
            ]);
            exit;
        }
        
        // Validate email format
        if (!filter_var($_POST['paypalEmail'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'Please enter a valid PayPal email address'
            ]);
            exit;
        }
        
        $_SESSION['paypal_email'] = filter_var($_POST['paypalEmail'], FILTER_SANITIZE_EMAIL);
        
        // Clear credit card data
        unset($_SESSION['card_last4']);
        unset($_SESSION['card_type']);
        unset($_SESSION['name_on_card']);
        unset($_SESSION['expiry_date']);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Checkout data saved successfully'
    ]);
    exit;
}

// Function to detect card type
function getCardType($cardNumber) {
    $cardNumber = preg_replace('/\s+/', '', $cardNumber);
    
    // Visa
    if (preg_match('/^4/', $cardNumber)) {
        return 'Visa';
    }
    // MasterCard
    if (preg_match('/^5[1-5]/', $cardNumber) || preg_match('/^2[2-7]/', $cardNumber)) {
        return 'MasterCard';
    }
    // American Express
    if (preg_match('/^3[47]/', $cardNumber)) {
        return 'American Express';
    }
    // Discover
    if (preg_match('/^6(?:011|5)/', $cardNumber)) {
        return 'Discover';
    }
    
    return 'Unknown';
}

echo json_encode([
    'success' => false,
    'message' => 'Invalid request'
]);
?>
