<?php
session_start();

// Function to validate phone number format
function validatePhoneNumber($number) {
    return preg_match('/^\+?\d{1,12}$/', $number);
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Validate form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $fname = sanitizeInput($_POST["fname"]);
    $lname = sanitizeInput($_POST["lname"]);
    $email = sanitizeInput($_POST["email"]);
    $ph_number = sanitizeInput($_POST["ph_number"]);
    $dob = sanitizeInput($_POST["dob"]);
    $bio = sanitizeInput($_POST["bio"]);

    // Initialize errors array
    $errors = [];

    // Validate first name and last name (no leading white space allowed)
    if (preg_match("/^\s+/", $fname) || preg_match("/^\s+/", $lname)) {
        $errors['name'] = "First name and last name should not contain leading white space.";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    // Validate phone number format
    if (!validatePhoneNumber($ph_number)) {
        $errors['ph_number'] = "Phone number should be maximum 12 digits, starting with '+' if international.";
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

        // Set success message
        $_SESSION['success'] = "Submission successful!";
    } else {
        // Store form data and errors in session to repopulate fields and display errors
        $_SESSION['form_data'] = [
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'ph_number' => $ph_number,
            'dob' => $dob,
            'bio' => $bio
        ];
        $_SESSION['errors'] = $errors;
    }

    // Redirect back to form page
    header("Location: form.html");
    exit();
}
?>
