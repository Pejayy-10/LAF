<?php
// Check if the HTTPS protocol is being used
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $uri = 'https://';
} else {
    $uri = 'http://';
}

// Append the host name and redirect to the main view page within the LAF folder
$uri .= $_SERVER['HTTP_HOST'] . '/LAF/main.php';
header('Location: ' . $uri);
exit;
#lafadmin
#laf12345
?>
