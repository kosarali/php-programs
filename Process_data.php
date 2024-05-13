<?php
session_start();

// Function to validate phone number format
function validatePhoneNumber($number) {
    return preg_match('/^\+?\d{1,12}$/', $number);
}

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize errors array
$errors = [];

// Validate form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $fname = sanitize_input($_POST["fname"]);
    $lname = sanitize_input($_POST["lname"]);
    $email = sanitize_input($_POST["email"]);
    $ph_number = sanitize_input($_POST["ph_number"]);
    $dob = sanitize_input($_POST["dob"]);
    $bio = sanitize_input($_POST["bio"]);

    // Validate first name and last name (no white space allowed)
    if (preg_match("/^\s+/", $fname) || preg_match("/^\s+/", $lname)) {
        $errors['name'] = "First name and last name should not contain leading white space.";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    // Validate phone number format
    if (!validatePhoneNumber($ph_number)) {
        $errors['phone'] = "Phone number should be maximum 12 digits, starting with '+' if international.";
    }

    // Validate biography length
    if (strlen($bio) > 50) {
        $errors['bio'] = "Biography should be maximum 50 characters long.";
    }

    // If no errors, save data to database
    if (empty($errors)) {
        // Save data to database (assuming you have a database connection)
        $conn = new mysqli('localhost', 'root', 'password', 'my_customers');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind parameters to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO Data2 (fname, lname, email, ph_number, date_of_birth, biography) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fname, $lname, $email, $ph_number, $dob, $bio);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        // Redirect back to form with success message
        $_SESSION['success'] = "Registration successful!";
        header("Location: form.html");
        exit();
    } else {
        // Store form data in session to repopulate fields
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
        $_SESSION['email'] = $email;
        $_SESSION['ph_number'] = $ph_number;
        $_SESSION['dob'] = $dob;
        $_SESSION['bio'] = $bio;
        $_SESSION['errors'] = $errors;

        // Redirect back to form without reloading
        header("Location: form.html");
        exit();
    }
}
?>
