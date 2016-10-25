<?php 

/* Creates new random key */
function make_auth_key($length = 10) {
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
        return $randomString;
}

/* Variables */
$timestring = time();
$authstring = make_auth_key();
$newsiteid = "$timestring-$authstring";

/* Extract Zip */
$zip = new ZipArchive;
    if ($zip->open("install-package.zip")){ 
        $path = getcwd() . "/app/"; 
        $path = str_replace("\\","/",$path);  
        $zip->extractTo($path);
        $zip->close(); 
    }
      
/* Create arrays with special chars */
$o = array('Ò','Ó','Ô','Õ','Ö','ò','ó','ô','õ','ö');

/* Remember to remove the slash at the beginning otherwise it will not work */
$oldname = 'app/install-package/';

/* Get the directory name */
$old_dir_name = substr($oldname, strrpos($oldname, '/') + 1);

/* Replace any special chars with your choice */
$new_dir_name = str_replace($o, 'O', $old_dir_name);

/* Define the new directory */
$newname = "app/$newsiteid" . $new_dir_name;

/* Renames the directory */
rename($oldname, $newname);

/* Mail Include */
include("Mail.php");

/* Prepare Mail */
$recipients = "enqinet@gmail.com";
$headers["From"] = "welcome@jerseymp.com";
$headers["To"] = "enqinet@gmail.com";
$headers["Subject"] = "Your New WordPress Site Was Deployed";
$mailmsg = "Hello, This is to let you know your new WordPress site has been installed successfully at http://example.com/app/$newsiteid.";

/* SMTP server name, port, user/passwd */
$smtpinfo["host"] = "mail.vps42975.mylogin.co";
$smtpinfo["port"] = "25";
$smtpinfo["auth"] = true;
$smtpinfo["username"] = "welcome@jerseymp.com";
$smtpinfo["password"] = "v8zjPO9o2G";

/* Create the mail object using the Mail::factory method */
$mail_object =& Mail::factory("smtp", $smtpinfo);

/* Ok send mail */
$mail_object->send($recipients, $headers, $mailmsg);


    
?>