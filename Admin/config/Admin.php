<?php

Class Admin extends PDO{
    function __construct($DB_USER, $DB_PASS){
        Parent::__construct('mysql:host='._HOST_.';port='._PORT_.';dbname='._DB_, $DB_USER, $DB_PASS);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    private function get_user($email){
        try{
            $stmt= $this->prepare("SELECT * FROM admin WHERE email= :em");
            $stmt->execute(array(
                ':em' => $email));
            $user= $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
            $user= false;
        }
        return $user;
    }

    private function if_exist($email){
        try{
            $stmt= $this->prepare("SELECT COUNT(*) FROM admin WHERE email= :em");
            $stmt->execute(array(
                ':em' => $email));
            $num= $stmt->fetchColumn();
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
            $num= false;
        }
        return $num;
    }
    
    public function Login($email, $pass){
       $user= $this->get_user($email);
       if($user){
            if(password_verify($pass, $user['password'])){
                $_SESSION['Admin']= $user['email'];
                header("Location: home.php");
            }else{
                $_SESSION['info'] = "<div id='info'>Incorrect email/password.</div>";
                header("Location: index.php");
            }
       }else{
            if($email === _ADMIN_){ //Check if it is lienced Admin
                $token= bin2hex( random_bytes(4) );
                $exp= date('Y-m-d H:i:s', strtotime('+5 minutes'));
                try{
                    $this->beginTransaction();
                    $register= $this->prepare("INSERT INTO admin(email, password) VALUES(:em, :pass)");
                    $register->execute(array(
                        ':em'=> $email,
                        ':pass'=> $token.$exp //use token & time as temporal password
                    ));
                    $admin_id= $this->lastInsertId();
                    $register= $this->prepare("INSERT INTO reset_password(admin_id, token, expire) VALUES(:id, :tk, :ex)");
                    $register->execute(array(
                        ':id'=> $admin_id,
                        ':tk'=> $token,
                        ':ex'=> $exp
                    ));
                    $this->commit();
                    $auth_url= _DOMAIN_."/admin/reset_password.php?token=$token&id=$admin_id";
                    echo "<a href='$auth_url'>Click here</a>";//print it out for now
                    die();
                    //use mail(); function to   send auth link..
                    $_SESSION['info'] = "<div id='info'>Authentication link sent to your mail.</div>";
                    header("Location: index.php");
                }catch(Exception $e){
                    $this->rollBack();
                    error_log("Database(Admin) error  ::::". $e->getMessage());
                    $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
                    header("Location: index.php");
                }
            }else{
                $_SESSION['info'] = "<div id='info'>Access denied.</div>";
                header("Location: index.php");
            }
        }
       
    }

    public function token_verification($token, $id){
        //Redirection and return not used because request is $_GET
        $ver= false;
        try{
            $stmt= $this->prepare("SELECT * FROM reset_password WHERE admin_id= :id AND token= :tk");
            $stmt->execute(array(
                ':id' => $id,
                ':tk' => $token
            ));
            $rsp= $stmt->fetch(PDO::FETCH_ASSOC);
            if($rsp){
                if($rsp['expire'] >= date('Y-m-d H:i:s')){
                    $stmt= $this->prepare("DELETE FROM reset_password WHERE admin_id= :id AND token= :tk");
                    $stmt->execute(array(
                        ':id' => $id,
                        ':tk' => $token
                    ));
                    $ver= true;
                }else{
                    $stmt= $this->prepare("DELETE FROM reset_password WHERE admin_id= :id AND token= :tk");
                    $stmt->execute(array(
                        ':id' => $id,
                        ':tk' => $token
                    ));
                    $this->query("DELETE FROM admin WHERE email='"._ADMIN_."'");//remove user from admin so that it can reset password again.
                    $_SESSION['info'] = "<div id='info'>Token expire</div>";
                    header("Location: index.php");
                }
            }else{
                $_SESSION['info'] = "<div id='info'>Invalid token</div>";
                header("Location: index.php");
            }
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
            header("Location: index.php");
        }
        
        return $ver;

    }

    public function create_password($pass, $id){
        $pass= password_hash($pass, PASSWORD_BCRYPT);
        try{
            $stmt= $this->prepare("UPDATE admin SET password=:pass WHERE id=:id");
            $stmt->execute(array(
                ':pass' => $pass,
                ':id' => $id
            ));
            $_SESSION['info'] = "<div id='info'>Password created successfully, Kindly login</div>";
            header("Location: index.php");
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
            header("Location: index.php");
        }
    }

    
}