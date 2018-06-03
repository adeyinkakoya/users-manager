<?php
require_once 'config.php';

class User{

private $db = null;
private $email;    
private $username;   
private $password;
private $uid;
private $sessioncode;        

public function __construct(\PDO $db){
    
    $this->db = $db;
}    

public function createUser($email,$username,$password){
    
    if($this->checkEmail($email)){
        echo 'Email address already exsist.';
    } elseif($this->checkUsername($username)){
        echo 'Username already exsist.';
    } else {
        $this->password = password_hash($password,PASSWORD_BCRYPT);
        $stmt = $this->db->prepare('INSERT INTO users (email,username,password) VALUES (?, ?, ? )');      
        if($stmt->execute(array($email,$username,$this->password))){
        echo 'Account successful created';     
        }       
    } 
}    

public function loginUser($username,$password){

    $stmt = $this->db->prepare('SELECT id,username,password FROM users WHERE username = ?');
        $stmt->execute(array($username));
            if($stmt->rowCount() > 0){
                $result = $stmt->fetch(PDO::FETCH_OBJ);
                if(password_verify($password, $result->password)){
                $this->setSession($result->username,$result->id);
                
            } else {
                echo 'wrong password';
                }
            } else {
                echo 'Username or email does not exist in the database.';
            }     
}    

public function logoutUser($uid){
    
    $this->unsetSession($uid);    
}
         
private function checkEmail($email){
    
    $stmt = $this->db->prepare('SELECT email FROM users WHERE email = ?');
        $stmt->execute(array($email));
        if($stmt->rowCount() > 0){
        return true;
        } else {
        return false;    
        }
}    
    
private function checkUsername($username){
    
    $stmt = $this->db->prepare('SELECT username FROM users WHERE username = ?');
        $stmt->execute(array($username));
        if($stmt->rowCount() > 0){
        return true;
        } else {
        return false;    
        }        
}    
    
private function setSession($username,$uid){
    
    $this->sessioncode = sha1(bin2hex(rand()));
    $_SESSION['id'] = $uid;    
    $_SESSION['username'] = $username;
    $_SESSION['session_code'] = $this->sessioncode;
        $stmt = $this->db->prepare('UPDATE users SET session_code = ? WHERE id = ?');
        if($stmt->execute(array($this->sessioncode,$uid))){
        header('Location: u/dashboard.php');    
    }   
}    
    
private function unsetSession($uid){
    
    $this->uid = $uid;
    $this->sessioncode = '';
    $stmt = $this->db->prepare('UPDATE users SET session_code = ? WHERE id = ?');
        if($stmt->execute(array($this->sessioncode,$this->uid))){
        session_unset();
        session_destroy();
        header('Location: ../index.php');    
    }    
}

}


?>
