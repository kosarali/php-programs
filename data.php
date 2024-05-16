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
        // Sample validation rules, modify as needed
        if (empty($this->formData['fname'])) {
            $this->errors[] = "First name is required.";
        }
        // Add more validation rules for other fields
    }

    private function saveToDatabase()
    {
        // Database connection and insertion code
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
