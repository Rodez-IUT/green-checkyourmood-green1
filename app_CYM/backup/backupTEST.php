<?php
include('dump.php');

// On se connecte à notre FTP
$ftp_server = "files.000webhost.com"; 
$ftp_connect = ftp_connect($ftp_server, 21, 5);
ftp_login($ftp_connect, 'saecheckyourmoodsave', 'CheckYourMood123');
ftp_pasv($ftp_connect, true);  // On active le mode passif si la connexion ne marche pas sans

$date = date('d-m-Y');
$source_file = getcwd()."/backup-" . date('d-m-y') . ".sql.rar";
$destination_file = "/backup-" . date('d-m-y') . ".sql.rar";

if (ftp_put($ftp_connect, $destination_file, $source_file , FTP_BINARY)) {
    echo "<br/>backup-$date.sql.rar. envoyé !\n";
} else {
    echo 'Erreur lors de l\'envoi du fichier ' . 'backup-' . date('d-m-y') . '.sql.rar';
}

unlink('backup-' . date('d-m-y') . '.sql.rar');
ftp_close($ftp_connect);
?>