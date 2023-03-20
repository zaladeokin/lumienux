<?php

Class Order extends PDO{
    function __construct($DB_USER, $DB_PASS){
        Parent::__construct('mysql:host='._HOST_.';port='._PORT_.';dbname='._DB_, $DB_USER, $DB_PASS);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function validate($data){
        //Repopulate form
        $_SESSION['customer_name']= $data['customer_name']; $_SESSION['customer_email']= $data['customer_email']; $_SESSION['customer_phone']= $data['customer_phone']; $_SESSION['customer_addr']= $data['customer_addr'];
        if( $data['customer_name']=="" || $data['customer_email']=="" || $data['customer_phone']=="" || $data['customer_addr']==""){
            $_SESSION['info'] = "<div id='info'>All field are required.</div>";
            return false;
        }elseif(!(preg_match("[@]", $data['customer_email']))){
            $_SESSION['info'] = "<div id='info'>Enter a valid email</div>";
            return false;
        }elseif(!(preg_match("/[0-9]{10,13}/", intval($data['customer_phone'])))){
            $_SESSION['info'] = "<div id='info'>Enter a valid phone number</div>";
            return false;
        }else{
            return true;
        }
    }

    public function approve($data, $product){
        try{
            $info= json_encode($data);
            $add= $this->prepare("INSERT INTO orders(tx_ref, email, phone, address, product_id, product_qty, amount, payment_info) VALUES(:tx_ref, :email, :tel, :add, :id, :qty, :amount, :info)");
            $add->execute(array(
                ':tx_ref' => $data->data->tx_ref,
                ':email' => $data->data->customer->email,
                ':tel' => $data->data->meta->phone_number,
                ':add' => $data->data->meta->address,
                ':id' => $data->data->meta->product_id,
                ':qty' => $data->data->meta->qty,
                ':amount' => $data->data->charged_amount,
                ':info' => $info
            ));
            //Update sold_product in product
            $this->update_stock(json_decode($data->data->meta->product_id), json_decode($data->data->meta->qty), $product);
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

}