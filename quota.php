<?php

    require_once(dirname(__FILE__) . '/config.php');  
    require_once(dirname(__FILE__) . '/app/global.php');  
    
    
    for($i=0; $i<=100; $i++):
        echo $define_sites[$i];
    endfor;
    
    

    $units = explode(' ', 'B KB MB GB TB PB');
    
    // 5 GB written in bytes. 
    // This is the quota you want to enforce.
    // $SIZE_LIMIT = 8709120; // test exceed
    $SIZE_LIMIT = 5368709120;
    
    // This is the path to the apps root
    $disk_path = "app";
    
    // This is the site id
    $disk_site = "1417778923-1719490728";
    
    // This is the folder you want to enforce the quota within
    $disk_used = foldersize("$disk_path/$disk_site");

    $disk_remaining = $SIZE_LIMIT - $disk_used;

    echo("<html><body>");
    echo('Storage Usage: ' . format_size($disk_used) . '<br>');
    echo('Storage Left: ' . format_size($disk_remaining) . '<br>');
    echo('Storage Quota: ' . format_size($SIZE_LIMIT) . '<br>');
    echo("</body></html>");

function foldersize($path) {
    $total_size = 0;
    $files = scandir($path);
    $cleanPath = rtrim($path, '/'). '/';

    foreach($files as $t) {
        if ($t<>"." && $t<>"..") {
            $currentFile = $cleanPath . $t;
            if (is_dir($currentFile)) {
                $size = foldersize($currentFile);
                $total_size += $size;
            }
            else {
                $size = filesize($currentFile);
                $total_size += $size;
            }
        }   
    }

    return $total_size;
}

function format_size($size) {
    global $units;

    $mod = 1024;

    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }

    $endIndex = strpos($size, ".")+3;

    return substr( $size, 0, $endIndex).' '.$units[$i];
}

if ($disk_used > $SIZE_LIMIT) {

    echo 'Status: Exceeded<br>';
    
    $suspended_site = "_SUSPENDED";

    /* Create arrays with special chars */
    $o = array('Ò','Ó','Ô','Õ','Ö','ò','ó','ô','õ','ö');

    /* Remember to remove the slash at the beginning otherwise it will not work */
    $oldname = "$disk_path/$disk_site/";

    /* Get the directory name */
    $old_dir_name = substr($oldname, strrpos($oldname, '/') + 1);

    /* Replace any special chars with your choice */
    $new_dir_name = str_replace($o, 'O', $old_dir_name);

    /* Define the new directory */
    $newname = "$disk_path/$disk_site$suspended_site/";

    /* Renames the directory */
    rename($oldname, $newname);   

} else {

    echo 'Status: Active<br>';
    
    $check_suspended = "_SUSPENDED";
    $site_dir_path = "$disk_path/$disk_site$check_suspended/";

    if (file_exists($site_dir_path)) {
    
        $suspended_site = "_SUSPENDED";

        /* Create arrays with special chars */
        $o = array('Ò','Ó','Ô','Õ','Ö','ò','ó','ô','õ','ö');

        /* Remember to remove the slash at the beginning otherwise it will not work */
        $oldname = "$disk_path/$disk_site$suspended_site/";

        /* Get the directory name */
        $old_dir_name = substr($oldname, strrpos($oldname, '/') + 1);

        /* Replace any special chars with your choice */
        $new_dir_name = str_replace($o, 'O', $old_dir_name);

        /* Define the new directory */
        $newname = "$disk_path/$disk_site/";

        /* Renames the directory */
        rename($oldname, $newname);  
               
    }
    
}

?>