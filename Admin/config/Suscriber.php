<?php

Class Suscriber extends PDO{
    private $opt= array("","upload_notification", "edit_notification");
    function __construct($DB_USER, $DB_PASS){
        Parent::__construct('mysql:host='._HOST_.';port='._PORT_.';dbname='._DB_, $DB_USER, $DB_PASS);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if($DB_USER == _ADMIN_USER_){
            $opt= $this->opt;
            try{
                $stmt= $this->query("SELECT COUNT(*) FROM config WHERE config_key= '$opt[1]' OR config_key= '$opt[2]'");
                $config= $stmt->fetchColumn();
            }catch(Exception $e){
                error_log("Database(Admin) error  ::::". $e->getMessage());
                $_SESSION['info'] = "<div id='info'>An error occurred in Suscriber Config.</div>";
                $config= false;
            }

            if($config !== false && $config < 2){
                try{
                    $this->query("INSERT INTO config(config_key, config_value) VALUES('$opt[1]', '1')");
                    $this->query("INSERT INTO config(config_key, config_value) VALUES('$opt[2]', '1')");
                }catch(Exception $e){
                    error_log("Database(Admin) error  ::::". $e->getMessage());
                    $_SESSION['info'] = "<div id='info'>An error occurred in Suscriber Config setup.</div>";
                }
            }  
        }
    }

    public function set_suscriber_config($key, $value){

        if(!isset($_SESSION['Admin'])) return false; // Resrict access to Admin

        $key= intval($key);
        if($key == 0){
            return false;
        }else{
            $key= $this->opt[$key];
        }

        try{
            $stmt= $this->prepare("UPDATE config SET config_value= :val WHERE config_key= '$key'");
            $stmt->execute(array(':val' => intval($value)));
            return true;
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            return false;
        }
    }

    public function get_suscriber_config($key){
        if(!isset($_SESSION['Admin'])) return false; // Resrict access to Admin

        $key= intval($key);
        if($key == 0){
            return false;
        }else{
            $key= $this->opt[$key];
        }

        try{
            $stmt= $this->query("SELECT * FROM config WHERE config_key= '$key'");
            $config= $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $config= false;
        }
        return $config['config_value'];
    }

    public function load_suscribers(){
        if(!isset($_SESSION['Admin'])) return false; // Resrict access to Admin

        $data= array();
        try{
            $stmt= $this->query("SELECT * FROM suscriber ORDER BY name");
            $data= array();
            while($d= $stmt->fetch(PDO::FETCH_ASSOC)){
                $data[]= $d;
            }
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $data= array('failed' => 'An error occurred.');
        }
        return json_encode($data);
    }

    private function send_newsletter($content){
        //Load Suscriber
        $suscribers= json_decode($this->load_suscribers());
        foreach($suscribers as $recipient){
            $intro= "<p>Hello ".$recipient->name.",</p>";
            //Send mail
            include_once("email.php");
            send_mail($recipient->email,"Lumienux Solar", $intro.$content);
        }
    }

    public function send_custom_mail($msg){
        if(!isset($_SESSION['Admin'])){// Resrict access to Admin
            $_SESSION['info'] = "<div id='info'>Access denied.</div>";
            header("Location: "._DOMAIN_."/index.php");
            return;
        }

        if($msg == ""){//Ensure mail is not empty
            $_SESSION['info'] = "<div id='info'>Content can't be empty</div>";
            header("Location: suscriber.php");
            return;
        }

        //Send newsletter
        $this->send_newsletter($msg);
        $_SESSION['info'] = "<div id='info'>Mail sent successfully</div>";
        header("Location: suscriber.php");
        return;
    }

    public function send_upload_mail(array $data, $product_id){
        if(!isset($_SESSION['Admin'])) return false; // Resrict access to Admin

        if($this->get_suscriber_config(1) != 1) return;//Check if Admin want to send notification to suscriber
        
        $checkout_url= _DOMAIN_."/checkout.php?id=$product_id";

        $content= <<<_pro
                        <strong>You might be interested in this product:</strong><br>
                        <p>$data[product_name] is now available for sale at the rate of $data[product_price].</p>
                        <p>$data[product_desc]</p>
                        <a href="$checkout_url"><button>View product</button></a>
                        _pro;
        
        //Send newsletter
        $this->send_newsletter($content);

        return;
    }

    public function send_edit_mail(array $data){
        if(!isset($_SESSION['Admin'])) return false; // Resrict access to Admin

        if($this->get_suscriber_config(2) != 1) return;//Check if Admin want to send notification to suscriber

        if($data['product_qty'] <= $data['qty_sold']) return;

        $checkout_url= _DOMAIN_."/checkout.php?id=$data[product_id]";

        $content= <<<_pro
                        <strong>New update on $data[product_name]</strong><br>
                        <p>$data[product_name] is now available for sale at the rate of $data[product_price].</p>
                        <p>$data[product_desc]</p>
                        <a href="$checkout_url"><button>View product</button></a>
                        _pro;
        
        //Send newsletter
        $this->send_newsletter($content);

        return;
    }

    private function if_exist($email){
        try{
            $stmt= $this->prepare("SELECT COUNT(*) FROM suscriber WHERE email= :email");
            $stmt->execute(array(':email' => $email));
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
                    //use mail() to send Notification for successful registration..
                    include_once("email.php");
                    $body= <<<_auth
                                <strong>Hi $data[scb_name],</strong><br>
                                <p> Your subscription to our newsletter was successful, We'll keep you updated on our products.</p><br>
                                <cite>Thanks.</cite>
                            _auth;
                    send_mail($data['scb_email'],"Subscription", $body);
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