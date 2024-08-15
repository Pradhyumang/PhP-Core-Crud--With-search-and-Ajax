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
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format.");
            }
            if (!preg_match('/^\d{10}$/', $phone_no)) {
                throw new Exception("Phone Number must be exactly 10 digits.");
            }

            $stmt = $conn->prepare("INSERT INTO user_details (first_name, last_name, phone_no, email, user_role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiss", $first_name, $last_name, $phone_no, $email, $roles);
            $stmt->execute();
            $stmt->close();
            echo "User added successfully";
            break;

        case 'update':
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
                $limit = 3; 
                $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
                $offset = ($page - 1) * $limit;
            
                $totalResult = $conn->query("SELECT COUNT(*) as total FROM user_details");
                $totalRows = $totalResult->fetch_assoc()['total'];
                $totalPages = ceil($totalRows / $limit);
            
                // Fetch users for the current page
                $stmt = $conn->prepare("SELECT * FROM user_details LIMIT ? OFFSET ?");
                $stmt->bind_param("ii", $limit, $offset);
                $stmt->execute();
                $result = $stmt->get_result();
                
                $users = [];
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
            
                // Send response with user data and pagination info
                echo json_encode([
                    'users' => $users,
                    'totalPages' => $totalPages,
                    'currentPage' => $page
                ]);
                break;
            
            case 'search':
               $searchTerm = trim($_POST['query']);
               if (empty($searchTerm)) {
                   throw new Exception("Search term is required.");
              }
               
                $limit = 3; 
                    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
                    $offset = ($page - 1) * $limit;
                
                    // Fetch total rows for pagination calculation
                    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM user_details WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone_no LIKE ? OR user_role LIKE ?");
                    $likeSearch = "%" . $searchTerm . "%";
                    $stmt->bind_param("sssss", $likeSearch, $likeSearch, $likeSearch, $likeSearch, $likeSearch);
                    $stmt->execute();
                    $totalResult = $stmt->get_result();
                    $totalRows = $totalResult->fetch_assoc()['total'];
                    $totalPages = ceil($totalRows / $limit);
                
                    // Fetch users for the current page
                    $stmt = $conn->prepare("SELECT * FROM user_details WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone_no LIKE ? OR user_role LIKE ? LIMIT ? OFFSET ?");
                    $stmt->bind_param("ssssiii", $likeSearch, $likeSearch, $likeSearch, $likeSearch, $likeSearch, $limit, $offset);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    $users = [];
                    while ($row = $result->fetch_assoc()) {
                        $users[] = $row;
                    }
                
                    // Send response with user data and pagination info
                    echo json_encode([
                        'users' => $users,
                        'totalPages' => $totalPages,
                        'currentPage' => $page
                    ]);
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
