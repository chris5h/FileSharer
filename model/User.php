<?php
require_once 'Db.php';
class User  {
    public static function Login($user, $pass){
        $conn = Db::conn();
        $sql = "SELECT username, `password` FROM settings where username = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);            
            $param_username = $user;            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);                
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    mysqli_stmt_bind_result($stmt, $id, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($pass, $hashed_password)){
                            return true;
                        } else{
                            return false;
                        }
                    }
                } else{
                    return false;
                }
            } else  {
                return false;
            }
        }        
    }

    public static function editUser($username, $password){
        $conn = Db::conn();
        $sql = "update settings settings set username = ?, password = ? ";
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $username, $param_password);
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            if(mysqli_stmt_execute($stmt)){
                return JSON_ENCODE(['status' => 'success']);
            } else{
                return JSON_ENCODE(['status' => 'error', 'Message' => mysqli_stmt_error($stmt)]);
                
            }
        }
    }
}