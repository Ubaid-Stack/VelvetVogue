<?php
session_start();
require_once './inc/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add':
        $full_name = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address_line1 = trim($_POST['address_line1'] ?? '');
        $address_line2 = trim($_POST['address_line2'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $state = trim($_POST['state'] ?? '');
        $zip_code = trim($_POST['zip_code'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $address_type = trim($_POST['address_type'] ?? 'home');
        $set_default = isset($_POST['set_default']) && $_POST['set_default'] === 'true' ? 1 : 0;
        
        // If setting as default, unset other defaults first
        if ($set_default) {
            $unsetQuery = "UPDATE addresses SET is_default = 0 WHERE user_id = ?";
            $unsetStmt = $conn->prepare($unsetQuery);
            $unsetStmt->bind_param("i", $user_id);
            $unsetStmt->execute();
            $unsetStmt->close();
        }
        
        // Insert new address
        $insertQuery = "INSERT INTO addresses (user_id, full_name, phone, address_line1, address_line2, city, state, zip_code, country, address_type, is_default) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("isssssssssi", $user_id, $full_name, $phone, $address_line1, $address_line2, $city, $state, $zip_code, $country, $address_type, $set_default);
        
        if ($insertStmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Address added successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to add address'
            ]);
        }
        $insertStmt->close();
        break;
        
    case 'update':
        $address_id = intval($_POST['address_id'] ?? 0);
        $full_name = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address_line1 = trim($_POST['address_line1'] ?? '');
        $address_line2 = trim($_POST['address_line2'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $state = trim($_POST['state'] ?? '');
        $zip_code = trim($_POST['zip_code'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $address_type = trim($_POST['address_type'] ?? 'home');
        $set_default = isset($_POST['set_default']) && $_POST['set_default'] === 'true' ? 1 : 0;
        
        // If setting as default, unset other defaults first
        if ($set_default) {
            $unsetQuery = "UPDATE addresses SET is_default = 0 WHERE user_id = ?";
            $unsetStmt = $conn->prepare($unsetQuery);
            $unsetStmt->bind_param("i", $user_id);
            $unsetStmt->execute();
            $unsetStmt->close();
        }
        
        // Update address
        $updateQuery = "UPDATE addresses SET full_name = ?, phone = ?, address_line1 = ?, address_line2 = ?, 
                        city = ?, state = ?, zip_code = ?, country = ?, address_type = ?, is_default = ? 
                        WHERE address_id = ? AND user_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sssssssssiis", $full_name, $phone, $address_line1, $address_line2, $city, $state, $zip_code, $country, $address_type, $set_default, $address_id, $user_id);
        
        if ($updateStmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Address updated successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update address'
            ]);
        }
        $updateStmt->close();
        break;
        
    case 'delete':
        $address_id = intval($_POST['address_id'] ?? 0);
        
        $deleteQuery = "DELETE FROM addresses WHERE address_id = ? AND user_id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("ii", $address_id, $user_id);
        
        if ($deleteStmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Address deleted successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete address'
            ]);
        }
        $deleteStmt->close();
        break;
        
    case 'set_default':
        $address_id = intval($_POST['address_id'] ?? 0);
        
        // Unset all defaults for this user
        $unsetQuery = "UPDATE addresses SET is_default = 0 WHERE user_id = ?";
        $unsetStmt = $conn->prepare($unsetQuery);
        $unsetStmt->bind_param("i", $user_id);
        $unsetStmt->execute();
        $unsetStmt->close();
        
        // Set new default
        $setQuery = "UPDATE addresses SET is_default = 1 WHERE address_id = ? AND user_id = ?";
        $setStmt = $conn->prepare($setQuery);
        $setStmt->bind_param("ii", $address_id, $user_id);
        
        if ($setStmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Default address updated'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to set default address'
            ]);
        }
        $setStmt->close();
        break;
        
    case 'get':
        $address_id = intval($_POST['address_id'] ?? 0);
        
        $getQuery = "SELECT * FROM addresses WHERE address_id = ? AND user_id = ?";
        $getStmt = $conn->prepare($getQuery);
        $getStmt->bind_param("ii", $address_id, $user_id);
        $getStmt->execute();
        $result = $getStmt->get_result();
        
        if ($address = $result->fetch_assoc()) {
            echo json_encode([
                'success' => true,
                'address' => $address
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Address not found'
            ]);
        }
        $getStmt->close();
        break;
        
    default:
        echo json_encode([
            'success' => false,
            'message' => 'Invalid action'
        ]);
        break;
}

$conn->close();
?>
