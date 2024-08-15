<?php
include 'dbconn.php';

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'insert':
            $first_name = trim($_POST['first_name']);
            $last_name = trim($_POST['last_name']);
            $phone_no = trim($_POST['phone_no']);
            $email = trim($_POST['email']);
            $roles = trim($_POST['roles']);
            if (empty($first_name) || empty($last_name) || empty($phone_no) || empty($email) || empty($roles)) {
                throw new Exception("All fields are required.");
            }

            if (strlen($first_name) < 5 || strlen($first_name) > 10) {
                throw new Exception("First Name must be between 5 and 10 characters.");
            }

            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format.");
            }

            // Validate phone number (example: must be digits and 10-15 characters long)
            if (!preg_match('/^\d{10}$/', $phone_no)) {
                throw new Exception("Phone Number must be exactly 10 digits.");
            }

            // Perform the insert operation
            $stmt = $conn->prepare("INSERT INTO user_details (first_name, last_name, phone_no, email, user_role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiss", $first_name, $last_name, $phone_no, $email, $roles);
            $stmt->execute();
            $stmt->close();
            echo "User added successfully";
            break;

        case 'update':
            // Validate each field
            $id = trim($_POST['id']);
            $first_name = trim($_POST['first_name']);
            $last_name = trim($_POST['last_name']);
            $phone_no = trim($_POST['phone_no']);
            $email = trim($_POST['email']);
            $roles = trim($_POST['roles']);

            if (empty($first_name) || empty($last_name) || empty($phone_no) || empty($email) || empty($roles)) {
                throw new Exception("All fields are required.");
            }

            if (strlen($first_name) < 5 || strlen($first_name) > 10) {
                throw new Exception("First Name must be between 5 and 10 characters.");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format.");
            }

            if (!preg_match('/^\d{10}$/', $phone_no)) {
                throw new Exception("Phone Number must be exactly 10 digits.");
            }

            // Perform the update operation
            $stmt = $conn->prepare("UPDATE user_details SET first_name = ?, last_name = ?, phone_no = ?, email = ?, user_role = ? WHERE id = ?");
            $stmt->bind_param("ssissi", $first_name, $last_name, $phone_no, $email, $roles, $id);
            $stmt->execute();
            $stmt->close();
            echo "User updated successfully";
            break;

        case 'delete':
            $id = trim($_POST['id']);
            if (empty($id)) {
                throw new Exception("ID is required.");
            }
            $stmt = $conn->prepare("DELETE FROM user_details WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            echo "User deleted successfully";
            break;

        case 'read':
            $result = $conn->query("SELECT * FROM user_details");
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
                // var_dump($row);
            }
            echo json_encode($users);
            break;
        case 'search':
            $searchTerm = trim($_POST['query']);
            if (empty($searchTerm)) {
                throw new Exception("Search term is required.");
            }
            $stmt = $conn->prepare("SELECT * FROM user_details WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone_no LIKE ? OR user_role LIKE ? ");
            $likeSearch = "%" . $searchTerm . "%";
            // var_dump($likeSearch);
            $stmt->bind_param("sssss", $likeSearch, $likeSearch, $likeSearch, $likeSearch,$likeSearch);
            $stmt->execute();
            $result = $stmt->get_result();
 
            $users = [];
            while ($row = $result->fetch_assoc()) {
            $users[] = $row;
            }
    
            if (count($users) > 0) {
            echo json_encode($users);
            } else {
            echo json_encode( "No users found");
            }
    
            $stmt->close();
            break;

        default:
            throw new Exception("Invalid action");
            break;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
