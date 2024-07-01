<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="inscription.css">
    <title>Registration Form</title>
</head>

<body>
  <div class="page">
    <img src="rental-car-logo.jpg" alt="logo">
    <div class="registre">
    <form action="inscription.php" method="post">
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" placeholder="Enter your first name">

        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" placeholder="Enter your last name">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email">
        <?php
        include('connexion.php');

        if (isset($_POST['btn'])) {
            # code...
            if (
                empty($_POST['firstName']) ||
                empty($_POST['lastName']) ||
                empty($_POST['email']) ||
                empty($_POST['password'])
            ) {
                # code...
                echo "<script>alter('please fill all the inputs')</script>";
            } else {
                $sql = "SELECT email FROM Clients";
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                $exist = false;
                while ($row = $stmt->fetch()) {
                    # code...
                    if ($row['email'] == $_POST['email']) {
                        # code...
                        $exist = true;
                        break;
                    }
                }

                if ($exist) {
                    # code...
                    echo "type an other email <br>";
                } else {
                    # code...
                    $nom = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_SPECIAL_CHARS);
                    $prenom = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_SPECIAL_CHARS);
                    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

                    $sql = "INSERT INTO Clients(nom,prenom,email,motdepasse)
                            VALUES(:nom,:prenom,:email,:motdepasse)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([':nom' => $nom, ':prenom' => $prenom, ':email' => $email, ':motdepasse' => $password]);

                    header('location:index.php');
                }
            }
        }


        ?>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password">

        <button name="btn" type="submit">Register</button>
        <a href="index.php"><button name="log_btn" type="button">login</button></a>
    </form>
    </div>
  </div>
    
</body>

</html>

