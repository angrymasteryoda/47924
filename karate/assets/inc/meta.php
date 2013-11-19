<?php
include_once '../config/global.php';

echo '<link rel="shortcut icon" href="' . APP_URL . 'assets/img/favicon.ico" />';
echo '<meta name="author" content="Michael Risher" />';
echo '<meta name="url" content="' . APP_URL . '" />';

function loadClasses(){
    $paths = glob( '../libraries/class.*.php' );
    foreach($paths as $path){
        require_once($path);
    }
}
function t($title){
    echo '<title>' . $title . '</title>';
}

loadClasses();
Core::loadCss();
Core::loadJavascript();

	
