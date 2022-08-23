<?
require_once 'Db.php';
class Settings  {
    public static function Load() {
        $conn =  Db::conn();
        $query = "select * from settings";
        if($stmt = mysqli_prepare($conn, $query)){
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    define('protocol_type',  $row['protocol_type']);

                    define('use_bitly',  ($row['use_bitly'] == 1 ? true : false));
                    define('bitly_token',  $row['bitly_token']);

                    define('use_email',  ($row['use_email'] == 1 ? true : false));
                    define('email_notification',  $row['email_notification']);
                    define('smtp_from_address',  $row['smtp_from_address']);
                    
                    define('smtp_security',  ($row['smtp_security'] == 1 ? true : false));
                    define('smtp_username',  $row['smtp_username']);
                    define('smtp_password',  $row['smtp_password']);
                    define('smtp_server',  $row['smtp_server']);
                    define('smtp_port',  $row['smtp_port']);
                    define('smtp_security_type',  $row['smtp_security_type']);
                }
            }
        }
    }

    public static function Get() {
        $conn =  Db::conn();
        $query = "select * from settings";
        if($stmt = mysqli_prepare($conn, $query)){
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    return [
                        'protocol_type' => $row['protocol_type'],
                        'use_bitly' => ($row['use_bitly'] == 1 ? true : false),
                        'bitly_token' => $row['bitly_token'],
                        'use_email' => ($row['use_email'] == 1 ? true : false),
                        'email_notification' => $row['email_notification'],
                        'smtp_from_address' => $row['smtp_from_address'],                    
                        'smtp_security' => ($row['smtp_security'] == 1 ? true : false),
                        'smtp_username' => $row['smtp_username'],
                        'smtp_password' => $row['smtp_password'],
                        'smtp_server' => $row['smtp_server'],
                        'smtp_port' => $row['smtp_port'],
                        'smtp_security_type' => $row['smtp_security_type']
                    ];
                }
            }
        }
    }

    public static function Save() {
        $conn =  Db::conn();
        $query = "update settings set 
            protocol_type = ?,
            use_bitly = ?,
            bitly_token = ?,
            use_email = ?,
            email_notification = ?,
            smtp_username = ?,
            smtp_password = ?,
            smtp_server = ?,
            smtp_port = ?,
            smtp_security = ?,
            smtp_security_type = ?,
            smtp_from_address = ?            
        ";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sisissssiiis", 
            $_POST['settings_protocol'],
            $_POST['settings_bitly'],
            $_POST['settings_bitly_token'],
            $_POST['settings_email'],
            $_POST['settings_email_recip'],
            $_POST['settings_email_user'],
            $_POST['settings_email_pass'],
            $_POST['settings_email_server'],
            $_POST['settings_email_port'],
            $_POST['settings_email_login'],
            $_POST['settings_email_security'],
            $_POST['settings_email_sender']            
        );
        if ( $stmt === false ) {
            throw new Exception("Error, could not process data submitted.");    
        }
        mysqli_stmt_execute($stmt);
    }

}