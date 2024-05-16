<?php

class FormHandler
{
    private $errors = [];
    private $formData = [];

    public function handleFormSubmission()
    {
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->sanitizeInput();
            $this->validateInput();

            if (empty($this->errors)) {
                $this->saveToDatabase();
                $_SESSION['success'] = "Submission successful!";
            } else {
                $_SESSION['form_data'] = $this->formData;
                $_SESSION['errors'] = $this->errors;
            }

            $this->redirectToFormPage();
        }
    }

    private function sanitizeInput()
    {
        $this->formData['fname'] = $this->sanitize($_POST["fname"]);
        $this->formData['lname'] = $this->sanitize($_POST["lname"]);
        $this->formData['email'] = $this->sanitize($_POST["email"]);
        $this->formData['ph_number'] = $this->sanitize($_POST["ph_number"]);
        $this->formData['dob'] = $this->sanitize($_POST["dob"]);
        $this->formData['bio'] = $this->sanitize($_POST["bio"]);
    }

    private function validateInput()
    {
        if (empty($this->formData['fname'])) {
            $this->errors['fname'] = "First name is required.";
        }

        if (empty($this->formData['lname'])) {
            $this->errors['lname'] = "Last name is required.";
        }

        if (!filter_var($this->formData['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "Invalid email format.";
        }

        if (!preg_match('/^\+?\d{1,12}$/', $this->formData['ph_number'])) {
            $this->errors['ph_number'] = "Phone number should be maximum 12 digits, starting with '+' if international.";
        }

        if (preg_match("/^\s+/", $this->formData['fname']) || preg_match("/^\s+/", $this->formData['lname'])) {
            $this->errors['name'] = "First name and last name should not contain leading white space.";
        }

        if (strlen($this->formData['bio']) > 50) {
            $this->errors['bio'] = "Biography should be maximum 50 characters long.";
        }
    }

    private function saveToDatabase()
    {
        require_once 'Database.php';
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("INSERT INTO Data (fname, lname, email, ph_number, date_of_birth, biography) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $this->formData['fname'], $this->formData['lname'], $this->formData['email'], $this->formData['ph_number'], $this->formData['dob'], $this->formData['bio']);
        $stmt->execute();
        $stmt->close();

        $db->closeConnection();
    }

    private function redirectToFormPage()
    {
        header("Location: form1.php");
        exit();
    }

    private function sanitize($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }
}
?>
