<?php
$root_htaccess_file = '.htaccess';
$public_htaccess_file = 'public/.htaccess';
$disable_listing = 'Options -Indexes';
$redirect_rule = "RewriteEngine On\nRewriteCond %{REQUEST_URI} ^/htaccess_control/?$\nRewriteRule ^(.*)$ http://localhost/htaccess_control/about.html [R=301,L]";
$cache_control = "FileETag None\nHeader unset ETag\nHeader set Cache-Control \"max-age=0, no-cache, no-store, must-revalidate\"\nHeader set Pragma \"no-cache\"\nHeader set Expires \"Wed, 11 Jan 1984 05:00:00 GMT\"";

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

// Toggle directory listing in the root directory
if (isset($_POST['toggle_listing'])) {
    if (file_exists($root_htaccess_file)) {
        $htaccess_content = file_get_contents($root_htaccess_file);
        
        if (strpos($htaccess_content, $disable_listing) !== false) {
            // Remove the directive
            $htaccess_content = str_replace($disable_listing, '', $htaccess_content);
            file_put_contents($root_htaccess_file, $htaccess_content);
            $message = "Root directory listing enabled.";
        } else {
            // Add the directive
            $htaccess_content .= "\n" . $disable_listing . "\n" . $cache_control;
            file_put_contents($root_htaccess_file, $htaccess_content);
            $message = "Root directory listing disabled.";
        }
    } else {
        // Create .htaccess file with the directive
        file_put_contents($root_htaccess_file, $disable_listing . "\n" . $cache_control);
        $message = "Root directory listing disabled.";
    }
} elseif (isset($_POST['enable_listing'])) {
    if (file_exists($root_htaccess_file)) {
        $htaccess_content = file_get_contents($root_htaccess_file);
        $htaccess_content = str_replace($disable_listing, '', $htaccess_content);
        file_put_contents($root_htaccess_file, $htaccess_content);
        $message = "Root directory listing enabled.";
    } else {
        $message = "Root directory listing is already enabled.";
    }
} elseif (isset($_POST['add_public_htaccess'])) {
    // Add .htaccess to public folder to enable directory listing
    file_put_contents($public_htaccess_file, "Options +Indexes\n" . $cache_control);
    $message = "Directory listing enabled in the public folder.";
} elseif (isset($_POST['remove_public_htaccess'])) {
    // Remove .htaccess from public folder
    if (file_exists($public_htaccess_file)) {
        unlink($public_htaccess_file);
        $message = "Public folder .htaccess removed. Directory listing follows root setting.";
    } else {
        $message = "No .htaccess file found in the public folder.";
    }
} elseif (isset($_POST['add_redirect'])) {
    // Add redirect rule to root .htaccess
    if (file_exists($root_htaccess_file)) {
        $htaccess_content = file_get_contents($root_htaccess_file);
        if (strpos($htaccess_content, $redirect_rule) === false) {
            $htaccess_content .= "\n" . $redirect_rule . "\n" . $cache_control;
            file_put_contents($root_htaccess_file, $htaccess_content);
            $message = "Redirect rule added to root .htaccess.";
        } else {
            $message = "Redirect rule already exists in root .htaccess.";
        }
    } else {
        file_put_contents($root_htaccess_file, $redirect_rule . "\n" . $cache_control);
        $message = "Root .htaccess created with redirect rule.";
    }
} elseif (isset($_POST['remove_redirect'])) {
    // Remove redirect rule from root .htaccess
    if (file_exists($root_htaccess_file)) {
        $htaccess_content = file_get_contents($root_htaccess_file);
        if (strpos($htaccess_content, $redirect_rule) !== false) {
            $htaccess_content = str_replace($redirect_rule, '', $htaccess_content);
            file_put_contents($root_htaccess_file, trim($htaccess_content));
            $message = "Redirect rule removed from root .htaccess.";
        } else {
            $message = "Redirect rule not found in root .htaccess.";
        }
    } else {
        $message = "No .htaccess file found in the root folder.";
    }
} else {
    if (file_exists($root_htaccess_file)) {
        $htaccess_content = file_get_contents($root_htaccess_file);
        $listing_disabled = (strpos($htaccess_content, $disable_listing) !== false);
    } else {
        $listing_disabled = false;
    }
    $message = $listing_disabled ? "Root directory listing is currently disabled." : "Root directory listing is currently enabled.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Directory Listing and Redirect Control</title>
</head>
<body>
    <h1>Directory Listing and Redirect Control</h1>
    <p><?php echo $message; ?></p>
    <form method="post">
        <button type="submit" name="toggle_listing">Toggle Root Directory Listing</button>
        <button type="submit" name="enable_listing">Enable Root Directory Listing</button>
    </form>
    <form method="post">
        <button type="submit" name="add_public_htaccess">Add .htaccess to Public Folder</button>
        <button type="submit" name="remove_public_htaccess">Remove .htaccess from Public Folder</button>
    </form>
    <form method="post">
        <button type="submit" name="add_redirect">Add Redirect to Root .htaccess</button>
        <button type="submit" name="remove_redirect">Remove Redirect from Root .htaccess</button>
    </form>
    <p>
        Click on the <strong>Toggle Root Directory Listing</strong> button to control whether users can see the file list when accessing <code>http://localhost/htaccess_control/</code>.
        The <code>Options -Indexes</code> directive is used to disable directory listing.
        <br>
        Click on the <strong>Enable Root Directory Listing</strong> button to explicitly enable directory listing.
    </p>
    <p>
        Click on the <strong>Add .htaccess to Public Folder</strong> button to enable directory listing in the public folder.
        <br>
        Click on the <strong>Remove .htaccess from Public Folder</strong> button to remove the .htaccess file from the public folder and follow the root directory listing setting.
    </p>
    <p>
        Click on the <strong>Add Redirect to Root .htaccess</strong> button to add a redirect rule to the root .htaccess file.
        <br>
        Click on the <strong>Remove Redirect from Root .htaccess</strong> button to remove the redirect rule from the root .htaccess file.(you might need to clear your browser's cache to take effect)
    </p>
    <p>
        <a href="http://localhost/htaccess_control/public/" target="_blank">Go to Public Folder</a>
        <br>
        <a href="http://localhost/htaccess_control/private/" target="_blank">Go to Private Folder</a>
    </p>
</body>
</html>
