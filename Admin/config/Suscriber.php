<?php
require_once('config.php');

Class Suscriber extends PDO{
    function __construct(){
        Parent::__construct('mysql:host='._HOST_.';port='._PORT_.';dbname='._DB_, _USER_, _PASS_);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function if_exist($email){
        try{
            $stmt= $this->prepare("SELECT COUNT(*) FROM suscriber WHERE email= :email");
            $stmt->execute(array(
                ':email' => $email));
            $num= $stmt->fetchColumn();
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
            $num= false;
        }
        return $num;
    }

    private function validate($data){
        //Repopulate form
        $_SESSION['scb_name']= $data['scb_name']; $_SESSION['scb_email']= $data['scb_email'];
        if( $data['scb_name']==""){
            $_SESSION['scb_name_err'] = "Enter your name";
            return false;
        }elseif( $data['scb_email']=="" || !(preg_match("[@]", $data['scb_email']))){
            $_SESSION['scb_email_err'] = "Enter a valid email";
            return false;
        }else{
            return true;
        }
    }

    public function add($data){
        $valid= $this->validate($data);
        if($valid){
            if( $this->if_exist($data['scb_email']) == 0){
                try{
                    $add= $this->prepare("INSERT INTO suscriber(email, name) VALUES(:email, :name)");
                    $add->execute(array(
                        'email' => $data['scb_email'],
                        ':name' => $data['scb_name']
                    ));
                    unset($_SESSION['scb_email']); unset($_SESSION['scb_name']);
                    $_SESSION['info'] = "<div id='info'>Thanks for suscribing to our newsletter.</div>";
                    header("Location: "._RQT_URL_);
                    return;
                }catch(Exception $e){
                    error_log("Database(Admin) error  ::::". $e->getMessage());
                    $_SESSION['scb_name_err'] = "An error occurred.";
                }
            }else{
                $_SESSION['scb_name_err'] = "The email provided has already been added to our Newsletter.";
            }
        }
        header("Location: "._RQT_URL_.'#newsletter');
    }

}