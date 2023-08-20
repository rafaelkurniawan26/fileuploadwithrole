<?php
session_start();

$isAdmin = ($_SESSION["email"] === "admin@gmail.com");

if (!$isAdmin) {
    die("You have no power to view this pics.");
}

$conn = mysqli_connect("localhost", "root", "", "fileupload");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$selectQuery = "SELECT email, filename FROM uploads";
$result = mysqli_query($conn, $selectQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin View</title>
</head>
<body>
<h1>Admin View - Uploaded Files</h1>
<?php
while ($row = mysqli_fetch_assoc($result)) {
    echo "Uploaded by: " . $row["email"] . "<br>";
    echo "<img src='" . $row["filename"] . "' alt='Uploaded Image'><br><br>";
}
mysqli_close($conn);
?>
</body>
</html>
