<!DOCTYPE html>
<html>
<head>
    <title>PHP Validation</title>
    <style type="text/css">
        .center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .error {
            color: red;
        }

        .vertical-center {
            margin: 0;
            position: absolute;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        .link {
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body bgcolor="#ADD8E6">
    <div class="center">
        <?php
        // Function to sanitize input
        function validate_input($data) {
            $data = trim($data); // Remove leading/trailing spaces
            $data = stripslashes($data); // Remove backslashes
            $data = htmlspecialchars($data); // Convert special characters to HTML entities
            return $data;
        }

        // Retrieve form data and validate
        $fname = validate_input($_POST["fname"]);
        $lname = validate_input($_POST["lname"]);
        $email = validate_input($_POST["email"]);
        $ph_number = validate_input($_POST["ph_number"]);
        $dob = validate_input($_POST["dob"]);
        $bio = validate_input($_POST["bio"]);

        // Create an array to store error messages
        $errors = [];

        // Validate first and last names should not start with a space
        if (preg_match("/^\s/", $fname)) {
            $errors['fname'] = "First name should not start with a space.";
        }
        if (preg_match("/^\s/", $lname)) {
            $errors['lname'] = "Last name should not start with a space.";
        }

        // Validate first and last names should contain only letters
        if (!preg_match("/^[a-zA-Z]+$/", $fname)) {
            $errors['fname'] = "First name should contain only letters.";
        }
        if (!preg_match("/^[a-zA-Z]+$/", $lname)) {
            $errors['lname'] = "Last name should contain only letters.";
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }

        // Validate phone number should be 10 digits
        if (!preg_match("/^[0-9]{10}$/", $ph_number)) {
            $errors['phone'] = "Phone number should contain 10 digits.";
        }

        // Validate biography length
        if (strlen($bio) > 50) {
            $errors['bio'] = "Biography should be a maximum of 50 characters.";
        }

        // If there are validation errors, display them and keep the form data
        if (!empty($errors)) {
            echo "<form action='form.php' method='post'>";

            // Display error for first name
            echo "<label>First Name: </label>";
            echo "<input type='text' name='fname' value='" . htmlspecialchars($fname) . "'>";
            if (isset($errors['fname'])) {
                echo "<span class='error'>" . $errors['fname'] . "</span>";
            }
            echo "<br>";

            // Display error for last name
            echo "<label>Last Name: </label>";
            echo "<input type='text' name='lname' value='" . htmlspecialchars($lname) . "'>";
            if (isset($errors['lname'])) {
                echo "<span class='error'>" . $errors['lname'] . "</span>";
            }
            echo "<br>";

            // Display error for email
            echo "<label>Email: </label>";
            echo "<input type='text' name='email' value='" . htmlspecialchars($email) . "'>";
            if (isset($errors['email'])) {
                echo "<span class='error'>" . $errors['email'] . "</span>";
            }
            echo "<br>";

            // Display error for phone number
            echo "<label>Phone Number: </label>";
            echo "<input type='text' name='ph_number' value='" . htmlspecialchars($ph_number) . "'>";
            if (isset($errors['phone'])) {
                echo "<span class='error'>" . $errors['phone'] . "</span>";
            }
            echo "<br>";

            // Display error for date of birth
            echo "<label>Date of Birth: </label>";
            echo "<input type='date' name='dob' value='" . htmlspecialchars($dob) . "'>";
            echo "<br>";

            // Display error for biography
            echo "<label>Biography: </label>";
            echo "<textarea name='bio'>" . htmlspecialchars($bio) . "</textarea>";
            if (isset($errors['bio'])) {
                echo "<span class='error'>" . $errors['bio'] . "</span>";
            }
            echo "<br>";

            echo "<button type='submit'>Submit</button>";
            echo "</form>";
        } else {
            // If no validation errors, process the data
            $conn = new mysqli('localhost', 'root', 'password', 'my_customers');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "INSERT INTO Data2 (fname, lname, email, ph_number, date_of_birth, biography)
                    VALUES ('$fname', '$lname', '$email', '$ph_number', '$dob', '$bio')";

            if ($conn->query($sql) === TRUE) {
                header("Location: form.html?success=Record%20Inserted%20Successfully");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        }
        ?>
    </div>
</body>
</html>
