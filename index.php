<?php
session_start();
include('connexion.php');


if (isset($_SESSION['error'])) {
    echo "<div id='error' class='error'>" . $_SESSION['error'] . "</div>";
    // Unset the error message after displaying it so it doesn't persist
    unset($_SESSION['error']);
}

// Clear session variables
session_unset();

if (isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM Clients WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $email_is_correct = false;
        $password_is_correct = false;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['email'] == $email) {
                $email_is_correct = true;
                if (password_verify($password, $row['motdepasse'])) {
                    $password_is_correct = true;
                    break; // No need to continue checking other records
                }
            }
        }

        if ($email_is_correct && $password_is_correct) {
            $_SESSION['id'] = $row['idClient'];
            $_SESSION['nom'] = $row['nom'];
            $_SESSION['prenom'] = $row['prenom'];
            header("location:acceuil.php");
        } else {
            if (!$email_is_correct) {
                $_SESSION['email_message'] = "Email not found!";
            }
            if (!$password_is_correct) {
                $_SESSION['password_message'] = "Wrong password!";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Authentication</title>
    <link rel="stylesheet" href="authentication.css">
</head>

<body>
    <div class="page">
        <img src="rental-car-logo.jpg" alt="logo">
        <div class="login">
            <form action="index.php" method="post">
                <div class="info">
                    <label for="user">User:</label>
                    <?php
                    if (isset($_POST['login']) && !empty($_SESSION['email_message'])) {
                        echo '<div class="feedback">' . $_SESSION['email_message'] . '</div>';
                    }
                    ?>
                    <input type="text" id="user" name="user" placeholder="Type your email!" required>
                </div>
                <div class="info">
                    <label for="password">Password:</label>
                    <?php
                    if (isset($_POST['login']) && !empty($_SESSION['password_message'])) {
                        echo '<div class="feedback">' . $_SESSION['password_message'] . '</div>';
                    }
                    ?>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div>
                    <button name="login" type="submit">Login</button>
                    <a href="inscription.php"><button name="registrer" type="button">registrer</button></a>
                </div>
        </div>
        </form>
    </div>

    <script>
        let errorMsg = document.getElementById('error');

        errorMsg.onmouseenter = function() {

            errorMsg.textContent = 'click to close the msg!';
            errorMsg.style.background = '#cf7b83';
            errorMsg.style.transition = 'background 1s ease';
        }

        errorMsg.onclick = function() {
            errorMsg.style.display = 'none';
        }
    </script>
</body>

</html>