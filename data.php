<!DOCTYPE html>
<html>
    <head>
        <title>PHP PAGE</title>
        <style type="text/css">
            .center
            {
             display: flex;
             justify-content: center;
             align-items: center;
            }

            .vertical-center
            {
             margin: 0;
             position: absolute;
             top: 50%;
             -ms-transform: translateY(-50%);
             transform: translateY(-50%);
             }
        </style>
    </head>
    <body bgcolor="#ADD8E6">
        <div class="center">
            <?php
                //validation input function to assign  calls
                function validate_input($data)
                {
                  $data = trim($data);
                  $data = stripcslashes($data);
                  $data = htmlspecialchars($data);
                  return $data;
                }

                $fname = validate_input($_POST["fname"]);
                $lname = validate_input($_POST["lname"]);
                $email = validate_input($_POST["email"]);
                $ph_number = validate_input($_POST["ph_number"]);
                $dob = validate_input($_POST["dob"]);
                $bio = validate_input($_POST["bio"]);

                //validation
                $error = [];
               /* if (!preg_match("/^[|s]+$/", $fname))
                    {
                     $error[] = "First name ($fname) should contain letters only";
                    }
                if (!preg_match("/^[a-zA-Z]+$/", $lname))
                    {
                     $error[] = "Last name ($lname) should contain letters only";
                    }*/
                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                    {
                    $error[] = "($email) Has an Invalid Email Formate";
                    }
                if (!preg_match("/^[0-9]{8,12}$/", $ph_number))
                    {
                     $error[] = "Phont Number should contain 10 digit";
                    }
                if (strlen($bio) > 50)
                    {
                     $error[] = "Maximum  50 characters is allowed";
                    }

                // If there are validation errors it displays them
                if (!empty($error))
                {
                    foreach ($error as $errors)
                    {
                     echo $errors . "<br>";
                    }
                }

                else
                {
                    // Create connection
                    $conn = new mysqli('localhost', 'root', 'password', 'my_customers');

                    if ($conn->connect_error)
                    {
                     die("Connection failed: " . $conn->connect_error);
                    }
                    // Insert into  table
                    $sql = "INSERT INTO Data2 (fname, lname, email, ph_number, date_of_birth, biography)
                        VALUES ('$fname', '$lname', '$email', '$ph_number', '$dob', '$bio')";

                    // If the connection is successful, run the query
                    if ($conn->query($sql) === TRUE)
                    {
                     echo "Record Inserted Successfully";
                    }
                    // If the query is not successful, display the error message
                    else
                    {
                     echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
            ?>
        </div>
        <div>
            <div class="center">
            <div class="vertical-center">
                <a href="form.html">Click here to go back</a>
        </div>
    </body>
</html>