<?php

Class DeliveryFee extends PDO{
    function __construct($DB_USER, $DB_PASS){
        Parent::__construct('mysql:host='._HOST_.';port='._PORT_.';dbname='._DB_, $DB_USER, $DB_PASS);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function load_state(){
        $result= array();
        try{
            $result= array();
            $stmt= $this->query("SELECT * FROM delivery_fee ORDER BY state");
            while($t = $stmt->fetch(PDO::FETCH_ASSOC)){
                $result[]= $t;
            }
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
        }
        return json_encode($result);
    }

    public function total_state(){
        try{
            $stmt= $this->query("SELECT COUNT(*) FROM delivery_fee");
            $num= $stmt->fetchColumn();
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $num= false;
        }
        return $num;
    }

    private function validate($data){
        if(strlen($data['state']) <= 0) return false;
        elseif( (!preg_match("/^[0-9]{1,}$/", $data['light'])) || (!preg_match("/^[0-9]{1,}$/", $data['medium'])) || (!preg_match("/^[0-9]{1,}$/", $data['high'])) ) return false;
        else return true;
    }

    public function if_exist($state){
        try{
            $stmt= $this->prepare("SELECT COUNT(*) FROM delivery_fee WHERE state= :state");
            $stmt->execute(array(
                ':state' => $state));
            $num= $stmt->fetchColumn();
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $num= false;
        }
        return $num;
    }

    public function get_state($id){
        try{
            $stmt= $this->prepare("SELECT * FROM delivery_fee WHERE id= :id");
            $stmt->execute(array(
                ':id' => $id));
            $state= $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $state= false;
        }
        return $state;
    }

    public function add_state($data){

        if(!isset($_SESSION['Admin']))return false; // Resrict access to Admin

        if(!$this->validate($data)) return false;

        try{
            $add= $this->prepare("INSERT INTO delivery_fee(state, light, medium, high) VALUES(:state, :light, :medium, :high)");
            $add->execute(array(
                ':state' => $data['state'],
                ':light' => $data['light'],
                ':medium' => $data['medium'],
                ':high' => $data['high']
            ));
            return true;
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            return false;
        }
    }

    public function edit_state($data){

        if(!isset($_SESSION['Admin'])) return false; // Resrict access to Admin

        if(!$this->validate($data)) return false;

        try{
            $edit= $this->prepare("UPDATE delivery_fee SET state= :state, light= :light, medium= :medium, high= :high WHERE id=:id");
            $edit->execute(array(
                ':state' => $data['state'],
                ':light' => $data['light'],
                ':medium' => $data['medium'],
                ':high' => $data['high'],
                ':id' => $data['id']
                ));
                return true;
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            return false;
        }

    }

    public function delete_state($id){
        if(!isset($_SESSION['Admin'])) return false; // Resrict access to Admin

        try{
            $stmt= $this->prepare("DELETE FROM delivery_fee WHERE id= :id");
            $stmt->execute(array(
                ':id' => $id));
            return true;
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            return false;
        }
    }

    public function calculate_charges(array $id, array $qty, $state, $product_PDO){
        $fee= 0;
        if ($state !="0"){
            $class= array('', 'light', 'medium', 'high');
            for($i=0; $i< count($id); $i++){
                $item= $product_PDO->get_product($id[$i]);
                $item_class= $class[intval($item['delivery_class'])];
                $state_fee= $this->get_state($state);
                $fee += $state_fee[$item_class] * $qty[$i]; 
            }
        }
        return $fee;
    }
}