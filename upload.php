<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $upload_directory = 'upload/';
    $upload_file = $upload_directory . basename($_FILES['file']['name']);
    
    if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_file)) {
        echo "File successfully uploaded.";
    } else {
        echo "File upload failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vulnerable File Upload</title>
</head>
<body>
    <h1>Vulnerable File Upload</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file" />
        <input type="submit" value="Upload" />
    </form>
</body>
</html>
