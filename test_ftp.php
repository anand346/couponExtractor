<?php
$ftp_server="anand346.host20.uk";
$ftp_user_name="anandho1";
$ftp_user_pass="rajanand9039";
$file = __DIR__.DIRECTORY_SEPARATOR."inspect_Element.txt";//tobe uploaded
$remote_file = "/public_html";

// set up basic connection
$conn_id = ftp_connect($ftp_server);

// login with username and password
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// upload a file
if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
echo "successfully uploaded $file\n";
exit;
} else {
echo "There was a problem while uploading $file\n";
exit;
}
// close the connection
ftp_close($conn_id);


?>