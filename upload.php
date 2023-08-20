<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $file = $_FILES["file"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!in_array($file['type'], $allowedTypes)) {
        die("Invalid file type. Only JPEG and PNG images are allowed.");
    }

    $isAdmin = ($email === "admin@gmail.com");

    $conn = mysqli_connect("localhost", "root", "", "fileupload");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $insertQuery = "INSERT INTO uploads (email, filename) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ss", $email, $targetFile);

    $targetDir = 'uploads/';
    $targetFile = $targetDir . uniqid() . '_' . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        if ($stmt->execute()) {
            echo "File uploaded and data stored successfully.";
        } else {
            echo "Error storing data in the database.";
        }
    } else {
        echo "Error uploading file.";
    }

    $stmt->close();
    $conn->close();

    $_SESSION["email"] = $email;

    if ($isAdmin) {
        header("Location: adminView.php");
        exit();
    } else {
        echo "File uploaded and data stored successfully.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
</head>
<body>
<h1>File Upload</h1>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>
    
    <label for="file">Upload Image (JPEG or PNG only):</label>
    <input type="file" id="file" name="file" accept=".jpeg, .jpg, .png" required><br>
    
    <button type="submit">Upload</button>
</form>
</body>
</html>
