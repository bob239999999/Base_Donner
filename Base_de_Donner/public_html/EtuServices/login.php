<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <?php
    // Check if there's an error message to display
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        echo "<p style='color: red;'>$error</p>";
    }

    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get user input
        $email = $_POST['email'];
        $password = $_POST['password'];
        /***
        // Execute the Python script and capture its output
        $cmd = escapeshellcmd("/Users/mac/.local/share/virtualenvs/j-znrU1gIY/bin/python " . "/Applications/XAMPP/xamppfiles/htdocs/EtuServices/exec.py". escapeshellarg($email) . ' ' . escapeshellarg($password));
        $output = shell_exec($cmd);
        // echo "<pre>$output</pre>";
        // Check if the output is not empty
        if (!empty($output)) {
            // Check the output to determine the state
            if (trim($output) == "blocked") {
                // User is blocked
                echo "Your account is blocked.";
            } elseif (is_numeric(trim($output)) && intval($output) > 0) {
                // User needs to wait for $output seconds before retrying
                echo "Please wait " . intval($output) . " seconds before retrying.";
            } elseif (trim($output) == "0") {
                // Access granted
                echo "Access granted.";
            } else {
                // Unexpected output
                echo "Unexpected output from Python script: " . htmlspecialchars($output);
            }
        
        } else {
            // No output received from the Python script
            echo "No output received from Python script.";
        }
        */
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>

<?php
function authenticate_user($email, $password) {
    // Connect to the database using prepared statements
    $servername = "localhost";
    $username = "root";
    $passwordi = ""; // Change this to your actual database password
    $dbname = "Login";

    // Create connection
    $conn = new mysqli($servername, $username, $passwordi, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement with parameterized query to fetch user details based on email
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, verify password
        $row = $result->fetch_assoc();
        // Compare plaintext password with the password stored in the database
        if ($password == $row["password"]) {
            return true; 
        } else {
            // Password is incorrect, return false
            // Close database connection
            $stmt->close();
            $conn->close();
            return false;
        }
    } else {
        // User not found, return false
        // Close database connection
        $stmt->close();
        $conn->close();
        return false;
    }
}
?>
