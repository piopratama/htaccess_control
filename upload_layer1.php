<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $upload_directory = 'upload/';
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
    $uploaded_file_extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    $upload_file = $upload_directory . basename($_FILES['file']['name']);

    // Validate file extension
    if (!in_array($uploaded_file_extension, $allowed_extensions)) {
        echo "Invalid file extension.";
        exit;
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_file)) {
        //we need to handle permission to add layer of security
        //chmod($upload_file, 0644);
        //we also need to add layer of protection with htaccess file
        //prevent to execute based on content (MIME sniffing)
        //         # .htaccess in the upload directory

        // # Deny execution of scripts
        // <FilesMatch "\.(php|phtml|php3|php4|php5|php7|phps|html|htm|shtml|sh|cgi|pl|py|exe|jsp|asp|aspx|cer|cfm|cfc|bat|vbs|js|jse|jar|vb|swf|htaccess|htpasswd)$">
        // Order Deny,Allow
        // Deny from all
        // </FilesMatch>

        echo "File successfully uploaded.";
    } else {
        echo "File upload failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>File Upload - Layer 1</title>
</head>
<body>
    <h1>File Upload - Layer 1</h1>
    <form action="upload_layer1.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file" />
        <input type="submit" value="Upload" />
    </form>
</body>
</html>
