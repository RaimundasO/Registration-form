<?php
// Enable PHP error reporting
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Includes
include "config.php";

// Variables
$first_name = $last_name = $email = $birthday = $gender = "";
$pdo = NULL;
$errors = array();

// Functions
function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') == $date ;
}

// Main
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, $pdo_init_params);

    // Set $_POST values to variables
    if (isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['birthday'], $_POST['gender'])) {
        if (!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['email']) && !empty($_POST['birthday']) && !empty($_POST['gender'])) {
            $first_name = ucfirst(strtolower(preg_replace('/\s+/', '', ($_POST['first_name']))));
            $last_name = ucfirst(strtolower(preg_replace('/\s+/', '', ($_POST['last_name']))));
            $email = strtolower($_POST['email']);
            $birthday = $_POST['birthday'];
            $gender = $_POST['gender'];

            if (!preg_match("/^[a-zA-Z]*$/", $first_name)) {
                $errors[] = "First name can only contain letters.";
            }

            if (!preg_match("/^[a-zA-Z]*$/", $last_name)) {
                $errors[] = "Last name can only contain letters.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Incorrect email address.";
            }

            if (!validateDate($birthday)) {
                $errors[] = "Incorrect date format.";
            }

            if (empty($errors)) {
                $sql = $pdo->prepare("INSERT INTO `users` (`first_name`, `last_name`, `email`, `birthday`, `gender`)
                          VALUES (:first_name, :last_name, :email, :birthday, :gender)");
                $sql->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                $sql->bindParam(':last_name', $last_name, PDO::PARAM_STR);
                $sql->bindParam(':email', $email, PDO::PARAM_STR);
                $sql->bindParam(':birthday', $birthday, PDO::PARAM_STR);
                $sql->bindParam(':gender', $gender, PDO::PARAM_INT);
                $sql->execute();

                $first_name = $last_name = $email = $birthday = $gender = "";
            } else {
                foreach ($errors as $error) {
                    echo "Error: ". $error ."<br>";
                }
            }

        } else {
            throw new Exception("Error: All fields required");
        }
    }

    //Login auth
    /*
    if (isset($_POST['email'])) {
        $sql = $pdo->query("SELECT * FROM `users` WHERE `email` = :email");
        $sql->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $sql->execute();

        $row = $sql->fetch(PDO::FETCH_ASSOC);
        echo $row['first_name'];
    }
    */

} catch (Exception $ex) {
    echo $error = $ex->getMessage();
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <div class="registration-container">
        <h1>Registration</h1>
        <form method="POST" action="">
            <input type="text" name="first_name" maxlength="20" value="<?=!empty($first_name) ? $first_name : ""?>" placeholder="First name" required>
            <input type="text" name="last_name" maxlength="20" value="<?=!empty($last_name) ? $last_name : ""?>" placeholder="Second name" required>
            <input type="text" name="email" value="<?=!empty($email) ? $email : ""?>" placeholder="Email address" required>
            <input type="text" id="datepicker" name="birthday" value="<?=!empty($birthday) ? $birthday : ""?>" placeholder="Birthday" required>

            <div class="radiobox">
                <label for="female">Female</label>
                <input type="radio" name="gender" id="female" value="female" <?=$gender == "female" ? "checked" : ""?> required>
            </div>

            <div class="radiobox">
                <label for="male">Male</label>
                <input type="radio" name="gender" id="male" value="male" <?=$gender == "male" ? "checked" : ""?> required>
            </div>

            <input type="submit" name="submit" value="Register">
        </form>
    </div>

    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>