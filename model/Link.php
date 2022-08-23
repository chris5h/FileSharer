<?php
require_once 'Bitly.php';
require_once 'Db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

if (!defined('DB_SERVER')){
    require_once '../inc/conf.php';
}
if (!class_exists('Settings')){
    require_once 'Settings.php';
    Settings::Load();
}

class Link{    
    public $guid;
    
    private $conn;
    private $path;
    private $notify;
    private $id;
    private $passwd = null;
    private $expires = null;
    private $bitly_url;
    private $active = false;

    function __construct($guid = null) {
        $this->conn =  Db::conn();
        if (!is_null($guid) and strlen($guid) > 0){
            $this->guid = $guid;
            $this->lookupLink();
        }
    }

    function addFile($path, $email, $protect, $pw, $expires){
        $expires = ($expires == "" ? null : $expires);
        if ($protect != 1){$pw = "";}
        $query = "INSERT INTO files (path, notify, protect, pw, expires) VALUES (?,?,?,?,?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "siiss", $path, $email, $protect, $pw, $expires);
        if ( $stmt === false ) {
            throw new Exception("Error, could not process data submitted.");    
        }        
        mysqli_stmt_execute($stmt);
        $query = "SELECT id, guid FROM files WHERE id = LAST_INSERT_ID()";
        if($stmt = mysqli_prepare($this->conn, $query)){
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $this->guid = $row['guid'];
                    $this->id = $row['id'];
                }
            }
        }
        if ($this->guid){
            return ['success' => true, 'id' => $this->id, 'guid' => $this->guid, 'expires' => $expires, "password" => $pw];
        }
    }

    function updateLink($email, $protect, $pw, $expires){
        $expires = ($expires == "" ? null : $expires);
        $query = "update files set notify = ?, protect = ?, pw = ?, expires = ? where id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "iissi", $email, $protect, $pw, $expires, $this->id);
        if ( $stmt === false ) {
            throw new Exception("Error, could not process data submitted.");    
        }
        mysqli_stmt_execute($stmt);
    }

    function lookupLink(){
        $query = "SELECT * FROM viewallfiles where guid = ?";
        if($stmt = mysqli_prepare($this->conn, $query)){
            mysqli_stmt_bind_param($stmt, "s", $this->guid);
            if(mysqli_stmt_execute($stmt)){                
                $result = mysqli_stmt_get_result($stmt);
                if ($result->num_rows > 0){                    
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        if ($row['active'] == 0){   //is this correct?  should it be ===
                            return ['success' => false, 'message' => 'Link is not active'];
                        }   else if (!is_null($row['date']) && (strtotime($row['date']) > strtotime('today'))){
                            return ['success' => false, 'message' => 'Link is expired'];
                        }   else    {
                            $this->path = $row['path'];
                            $this->notify = ($row['notify'] == 1 ? true : false);
                            $this->id = $row['id'];
                            $this->bitly_url = $row['bitly_url'];
                            $this->active = ($row['active'] == 1 ? true : false);
                            if ($row['protect'] == 1){
                                $this->passwd = $row['pw'];
                            }
                            if (!is_null($row['expires'])){
                                $this->expires = $row['expires'];
                            }
                            return ['success' => 'true', 'path' => $row['path']];
                        }
                    }
                }   else    {
                    return ['success' => false, 'message' => 'Link is invalid'];
                }
            }
        }
    }

    function getStats(){
        return [
            'guid' => $this->guid,
            'expiration' =>  $this->expires,
            'password' => $this->passwd,
            'notify' => ($this->notify == 1 ? true : false),
            'url' => protocol_type."://".$_SERVER['HTTP_HOST'].'/?guid='.$this->guid,
            'id' => $this->id,
            'bitly_url' => $this->bitly_url,
            'file_path' => $this->path
        ];
    }


    function deactivateLink(){
        $query = "update files set active = 0 where guid = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $this->guid);
        mysqli_stmt_execute($stmt);
    }

    function deleteLink(){
        $querys = [
            "DELETE FROM bitly_links WHERE file_id = (SELECT id FROM files WHERE guid = ?)",
            "DELETE FROM download_logs WHERE file_id = (SELECT id FROM files WHERE guid = ?)",
            "DELETE FROM files WHERE guid = ?"
        ];
        foreach ($querys as $query){
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $this->guid);
            mysqli_stmt_execute($stmt);
        }
    }
    function activateLink(){
        $query = "update files set active = 1 where guid = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $this->guid);
        mysqli_stmt_execute($stmt);
    }

    function checkFile(){
        if (!$this->active){
            return ['success' => false, 'status' => 'invalid'];
        }   else if(!is_null($this->passwd)){
            return ['success' => false, 'status' => 'password'];
        }   else if(!is_null($this->expires) && (strtotime('today') > strtotime($this->expires))){
            return ['success' => false, 'status' => 'expired'];
        }   else    {
            return ['success' => true];
        }
    }

    function downloadFile($pw = null){
        if (!is_null($this->passwd) && $pw != $this->passwd){
            return ['success' => false, 'status' => 'invalid'];
        }
        if (file_exists($this->path)) {
            $query = "INSERT INTO download_logs (file_id, ip_address) VALUES (?,?)";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "is", $this->id, $_SERVER['REMOTE_ADDR']);
            if ( $stmt === false ) {
                throw new Exception("Error, could not process data submitted.");    
            }
            mysqli_stmt_execute($stmt);
            if ($this->notify && use_email){
                $subject = "File Download Notification";                
                $body = "Hello.\r\nTHis is to let you know that ".basename($this->path)." has been downloaded by ".$_SERVER['REMOTE_ADDR']." at ".date("g:i:sa m/d/Y", strtotime('now')).".";
                $this->sendMail($subject, $body);
            }
            ini_set('memory_limit', (filesize($this->path)*1.2));
            set_time_limit(28800);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($this->path));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($this->path));
            ob_clean();
            flush();
            readfile($this->path);
        }
    }

    function shortenLink($url){
        $this->lookupLink();
        $ralph = new Bitly;
        $r = $ralph->shortenLink($url);
        $query = "INSERT INTO bitly_links (file_id, bitly_id, bitly_url) VALUES (?,?,?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "iss", $this->id, $r['id'], $r['link']);
        if ( $stmt === false ) {
            throw new Exception("Error, could not process data submitted.");    
        }
        mysqli_stmt_execute($stmt);
        if ($r['link']){
            return ['success' => true, 'link' => $r['link']];
        }   else    {
            return ['success' => false, 'message' => 'Error generating Bitly link.'];
        }
    }

    function sendMail($subject, $body){
		$email = new PHPMailer(true);
        try {
            $email->isSMTP();
            $email->Host       = smtp_server;
            $email->Port       = smtp_port;       
            if (smtp_security){
                $email->SMTPAuth   = true;
                $email->Username   = smtp_username;                     // SMTP username
                $email->Password   = smtp_password;                               // SMTP password      
                if (smtp_security_type > 0){
                    $email->SMTPSecure = (smtp_security_type == 1 ? 'ssl' : 'tls');
                }
            }
            $email->AddAddress(email_notification);
            $email->SetFrom(smtp_from_address, "File Downloader"); //Name is optional
            $email->Subject = $subject;
            $email->Body = $body;
            $email->Send();
        } catch (Exception $e) {
            print "Error sending mail.  Mail Error: {$email->ErrorInfo}";
        }                
    }

    static public function testEmail($smtp_server, $smtp_port, $smtp_security, $smtp_username, $smtp_password, $smtp_security_type, $email_notification, $smtp_from_address){
		$email = new PHPMailer(true);
        try {
            $email->isSMTP();
            $email->Host       = $smtp_server;
            $email->Port       = $smtp_port;       
            if ($smtp_security == 1){
                $email->SMTPAuth   = true;
                $email->Username   = $smtp_username;                     // SMTP username
                $email->Password   = $smtp_password;                               // SMTP password      
                if ($smtp_security_type > 0){
                    $email->SMTPSecure = ($smtp_security_type == 1 ? 'ssl' : 'tls');
                }
            }
            $email->AddAddress($email_notification);
            $email->SetFrom($smtp_from_address, "File Downloader"); //Name is optional
            $email->Subject = "Test Email from File Sender";
            $email->Body = "This is confirmation that the email settings worked.";
            $email->Send();
            return ["success" => "true"];
        } catch (Exception $e) {
            return ["success" =>false, "message" => "Error sending mail.  Mail Error: {$email->ErrorInfo}"];
        }                
    }

    static public function getAllLinks(){
        $conn =  Db::conn();
        $query = 'SELECT * FROM viewallfiles';
        if($stmt = mysqli_prepare($conn, $query)){
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $list[] = array_merge($row, [
                        'url' => protocol_type."://".$_SERVER['HTTP_HOST'].'/?guid='.$row['guid'], 
                        'filename' => basename($row['path']),
                        'short_exp' => $row['expires'] == '' ? '' : date('m/d/y', strtotime($row['expires'])),
                        'short_dt' => date('m/d/y', strtotime($row['dt']))
                    ]);
                }
            }
        }
        return $list;
    }

    static public function getDownloads(){
        $conn =  Db::conn();
        $query = 'select * from viewalldownloads ORDER BY path, dt desc';
        if($stmt = mysqli_prepare($conn, $query)){
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $list[] = array_merge($row, [
                        'filename' => basename($row['path']),
                        'dt_eng' => date('m/d/Y h:i A',strtotime( $row['dt'] ))
                    ]);
                }
            }
        }
        return $list;
    }
}

?>