<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    
    <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if all required fields are filled
        if (isset($_POST["email"]) && isset($_POST["firstname"]) && isset($_POST["lastname"]) && isset($_POST["password"])) {
            // Retrieve form data
            $email = $_POST["email"];
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $password = $_POST["password"];

            // Database connection parameters
            $servername = "localhost";
            $username = "root"; // Change to your database username
            $password_db = ""; // Change to your database password
            $dbname = "Login"; // Change to your database name
            // Create connection
            $conn = new mysqli($servername, $username, $password_db, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare SQL statement to insert user data into the database
            $sql = "INSERT INTO users (email, firstname, lastname, password) VALUES (?, ?, ?, ?)";
            
            // Prepare and bind parameters
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $email, $firstname, $lastname, $password);

            // Execute SQL statement
            if ($stmt->execute()) {
                echo "Inscription réussie.";
            } else {
                echo "Erreur: " . $sql . "<br>" . $conn->error;
            }

            // Close statement and connection
            $stmt->close();
            $conn->close();
        } else {
            // Display an error message if any required field is missing
            echo "Tous les champs sont obligatoires.";
        }
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="firstname">Prénom:</label><br>
        <input type="text" id="firstname" name="firstname" required><br>
        <label for="lastname">Nom:</label><br>
        <input type="text" id="lastname" name="lastname" required><br>
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>
