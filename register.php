<?php

session_start();

$db['host'] = "localhost"; 
$db['user'] = "root"; 
$db['pass'] = "daria"; 
$db['name'] = "sleeptacker";

$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name']);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    //echo "Connected successfully";
}

if (isset($_POST['usernamel'])) {
    $usernamel = $_POST['usernamel'];
    $passwordl = $_POST['passwordl'];
    $query = "SELECT * FROM user WHERE username = '$usernamel' AND password = '$passwordl'";
    $result = $conn->query($query);

    if ($result->num_rows === 1) {
        $_SESSION['username'] = $usernamel;
        header("Location: userpage.html");
        exit;
    } else {
        $error_message = "Invalid username or password";
    }
} 

elseif (isset($_POST['usernames'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $usernames = $_POST['usernames'];
    $passwords = $_POST['passwords'];
    $query = "INSERT INTO user (username, first_name, last_name, password) VALUES ('$usernames', '$first_name', '$last_name', '$passwords')";
    
    if ($conn->query($query) === TRUE) {
        $_SESSION['username'] = $usernames;
        header("Location: userpage.html");
        exit;
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

echo $error_message;
$conn->close();

?>
