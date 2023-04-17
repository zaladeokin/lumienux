<?php

Class Order extends PDO{
    function __construct($DB_USER, $DB_PASS){
        Parent::__construct('mysql:host='._HOST_.';port='._PORT_.';dbname='._DB_, $DB_USER, $DB_PASS);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function validate($data){
        //Repopulate form
        $_SESSION['customer_name']= $data['customer_name']; $_SESSION['customer_email']= $data['customer_email']; $_SESSION['customer_phone']= $data['customer_phone']; $_SESSION['customer_addr']= $data['customer_addr']; $_SESSION['state']= $data['state'];
        if( $data['customer_name']=="" || $data['customer_email']=="" || $data['customer_phone']=="" || $data['customer_addr']==""){
            $_SESSION['info'] = "<div id='info'>All field are required.</div>";
            return false;
        }elseif(!(preg_match("[@]", $data['customer_email']))){
            $_SESSION['info'] = "<div id='info'>Enter a valid email</div>";
            return false;
        }elseif(!(preg_match("/[0-9]{10,13}/", intval($data['customer_phone'])))){
            $_SESSION['info'] = "<div id='info'>Enter a valid phone number</div>";
            return false;
        }elseif($data['state']== "0"){
            $_SESSION['info'] = "<div id='info'>State is required.</div>";
            return false;
        }else{
            return true;
        }
    }

    public function approve($data, $product, $mail_content){
        try{
            $info= json_encode($data);
            $add= $this->prepare("INSERT INTO orders(tx_ref, email, amount, payment_info) VALUES(:tx_ref, :email, :amount, :info)");
            $add->execute(array(
                ':tx_ref' => $data->data->tx_ref,
                ':email' => $data->data->customer->email,
                ':amount' => $data->data->charged_amount,
                ':info' => $info
            ));
            //Update sold_product in product
            $this->update_stock(json_decode($data->data->meta->product_id), json_decode($data->data->meta->qty), $product);
            //Send mail
            include_once("email.php");
            send_mail($data->data->customer->email,"Order Completed (Lumienux Solar)", $mail_content);
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
        }
    }

    private function update_stock(array $products_id, array $product_qty, $product){
        for($i=0; $i< count($products_id); $i++){
            //Get sold_product value
            $data= $product->get_product($products_id[$i]);
            $new_value= intval($data['sold_product']) + $product_qty[$i];
            //Update product information
            try{
                $update= $this->prepare("UPDATE product SET sold_product= :qty_sold WHERE id=:id");
                $update->execute(array(
                    'qty_sold' => $new_value,
                    'id' => $products_id[$i]
                ));
            }catch(Exception $e){
                error_log("Database(Admin) error  ::::". $e->getMessage());
                $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
            }
        }
    }

    public function total_order($status= false, $advance_filter= false){
        
        if(!isset($_SESSION['Admin'])){// Resrict access to Admin
            return false;
        }

        if($advance_filter){
            $filter= ($status !== false) ? "WHERE (status= '$status') AND ($advance_filter) AND (deleted= '0')" : "WHERE $advance_filter AND (deleted= '0')";
        }else{
            $filter= ($status !== false) ? "WHERE (status= '$status') AND (deleted= '0')" : "WHERE deleted= '0'";
        }
        try{
            $stmt= $this->query("SELECT COUNT(*) FROM orders $filter");
            $num= $stmt->fetchColumn();
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $num= false;
        }
        return $num;
    }

    public function load_order($status= false, $advance_filter= false){
        
        if(!isset($_SESSION['Admin'])){// Resrict access to Admin
            $_SESSION['info'] = "<div id='info'>Access denied.</div>";
            header("Location: "._DOMAIN_."/index.php");
            return;
        }

        if($advance_filter){
            $filter= ($status !== false) ? "WHERE (status= '$status') AND ($advance_filter) AND (deleted= '0')" : "WHERE $advance_filter AND (deleted= '0')";
        }else{
            $filter= ($status !== false) ? "WHERE (status= '$status') AND (deleted= '0')" : "WHERE deleted= '0'";
        }
        
        try{
            $stmt= $this->query("SELECT id, tx_ref, email, amount FROM orders $filter ORDER BY id DESC");
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
            $stmt= false;
        }
        return $stmt;
    }

    public function get_order($id){
        if(!isset($_SESSION['Admin'])){// Resrict access to Admin
            return false;
        }

        $exist= $this->if_exist($id);
        if($exist == 0 && $exist ===false){
            $order= false;
            return;
        }
        
        try{
            $stmt= $this->prepare("SELECT payment_info, status FROM orders WHERE (id= :id)  AND (deleted= '0')");
            $stmt->execute(array(
                ':id' => $id));
            $order= $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $order= false;
        }
        return $order;
    }

    private function if_exist($id){
        try{
            $stmt= $this->prepare("SELECT COUNT(*) FROM orders WHERE (id= :id) AND (deleted= '0')");
            $stmt->execute(array(
                ':id' => $id));
            $num= $stmt->fetchColumn();
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $num= false;
        }
        return $num;
    }

    public function action($id, $action){
        if(!isset($_SESSION['Admin'])){// Resrict access to Admin
            return false;
        }
        if( $this->if_exist($id) == 0){
            return false;
        }
        if($action =='complete'){
            $sql= "UPDATE orders SET status= '1' WHERE id='$id'";
            $msg= "<span style='color:#008000;'>Completed</span>";
        }elseif($action == 'delete'){
            $sql= "UPDATE orders SET deleted= '1' WHERE id='$id'";
            $msg= "<span style='color:#ff0000;'>Deleted</span>";
        }else{
            $sql= false;
            $msg= "<span style='color:#ff0000;'>Try again</span>";
        }

        try{
            if($sql) {
                $this->query($sql);

                return json_encode(array('message'=> $msg));
            }else{
                return false;
            }
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            return false;
        }
    }
}