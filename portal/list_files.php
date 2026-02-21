<?php
// list_files.php
echo "<pre>";
$files = scandir('.');
foreach ($files as $file) {
    if (is_file($file)) {
        echo $file . " - " . filesize($file) . " bytes\n";
    }
}
echo "</pre>";
?>