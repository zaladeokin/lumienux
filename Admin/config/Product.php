<?php

Class Product extends PDO{
    function __construct($DB_USER, $DB_PASS){
        Parent::__construct('mysql:host='._HOST_.';port='._PORT_.';dbname='._DB_, $DB_USER, $DB_PASS);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function if_exist($p_name){
        try{
            $stmt= $this->prepare("SELECT COUNT(*) FROM product WHERE name= :name");
            $stmt->execute(array(
                ':name' => $p_name));
            $num= $stmt->fetchColumn();
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
            $num= false;
        }
        return $num;
    }

    private function validate($data){
        //Get product Data from DataBase to validate Quantity sold
        if(isset($data['qty_sold'])){
            $_SESSION['qty_sold']= $data['qty_sold'];//repopulate form
            $item= $this->get_product($data['product_id']);
            $item_qty= $item['stock'];
            if($data['qty_sold'] > $item_qty){
                $_SESSION['qty_sold_err'] = "value exceed stock quantity.";
                $item_qty_valid= false;
            }elseif($data['qty_sold'] < 0){
                $_SESSION['qty_sold_err'] = "Invalid value.";
                $item_qty_valid= false;
            }else{
                $item_qty_valid= true;
            }
        }else{//For Upload case where quantity sold is not set
            $item_qty_valid= true;
        }

        //Repopulate form
        $_SESSION['p_name']= $data['product_name']; $_SESSION['p_desc']= $data['product_desc']; $_SESSION['p_price']= $data['product_price']; $_SESSION['p_qty']= $data['product_qty']; $_SESSION['p_cat']= $data['category']; $_SESSION['p_dc']= $data['delivery'];
        if( $data['product_name']=="" /* || strlen($data['product_name] > 25) */ ){
            $_SESSION['name_err'] = "Product name too long or short.";
            return false;
        }elseif( $data['product_desc']=="" /* || strlen($data['product_name] > 500) */ ){
            $_SESSION['desc_err'] = "Product description too long or short.";
            return false;
        }elseif( $data['product_price'] <= 0 || $data['product_qty'] <= 0){
            $_SESSION['price_err'] = $_SESSION['qty_err'] = "Invalid price or quantity.";
            return false;
        }elseif(!$item_qty_valid){
            return false;
        }elseif( $data['category'] == 0 ){
            $_SESSION['cat_err'] = "Select a category for your product.";
            return false;
        }elseif( $data['delivery'] == 0 ){
            $_SESSION['delivery_err'] = "Select Delivery class.";
            return false;
        }elseif( $_FILES['product_img']['size'] > 1000000 ){ //Ensure file is not above 1MB
            $_SESSION['img_err'] = "Image must not exceed 1mb.";
            return false;
        }else{
            switch( $_FILES['product_img']['type'] ){
                //check file type and create file extention, return false if not image.
                case 'image/jpeg': $ext = '.jpg'; break;
                case 'image/gif': $ext = '.gif'; break;
                case 'image/png': $ext = '.png'; break;
                case 'image/tiff': $ext = '.tif'; break;
                default: $ext = ''; break;
            }
            if ( $ext != '' ){
                $_FILES['product_img']['name']= "pro_".time().$ext;
                return true;
            }else{
                $_SESSION['img_err'] = "Invalid image.";
                return false;
            }
        }
    }

    public function upload($data, $suscriber){
        if(!isset($_SESSION['Admin'])){// Resrict access to Admin
            $_SESSION['info'] = "<div id='info'>Access denied.</div>";
            header("Location: "._DOMAIN_."/index.php");
            return;
        }
        $valid= $this->validate($data);
        if($valid){
            if( $this->if_exist($data['product_name']) == 0){
                try{
                    $upload= $this->prepare("INSERT INTO product(name, description, price, stock, category, img, delivery_class) VALUES(:name, :desc, :price, :qty, :cat, :img, :dc)");
                    $upload->execute(array(
                        ':name' => $data['product_name'],
                        ':desc' => $data['product_desc'],
                        ':price' => $data['product_price'],
                        ':qty' => $data['product_qty'],
                        ':cat' => $data['category'],
                        ':img' => $_FILES['product_img']['name'],
                        ':dc' => $data['delivery']
                    ));
                    move_uploaded_file($_FILES['product_img']['tmp_name'], _ROOT_."/img/product/".$_FILES['product_img']['name']);
                    unset($_SESSION['p_name']); unset($_SESSION['p_desc']); unset($_SESSION['p_price']); unset($_SESSION['p_qty']); unset($_SESSION['p_cat']);

                    $suscriber->send_upload_mail($data, $this->lastInsertId());//Send mail to suscriber

                    $_SESSION['info'] = "<div id='info'>Product uploaded successfully.</div>";
                    header("Location: product.php?action=upload");
                }catch(Exception $e){
                    error_log("Database(Admin) error  ::::". $e->getMessage());
                    $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
                    header("Location: product.php?action=upload");
                }
            }else{
                $_SESSION['info'] = "<div id='info'>Product with name '($data[product_name])' already exist in the database.</div>";
                header("Location: product.php?action=upload");
            }
        }else{
            $_SESSION['info'] = "<div id='info'>Invalid input.</div>";
            header("Location: product.php?action=upload");
        }
    }
    
    public function search($keyword, $category= false){
        $keyword= filter_var($keyword, FILTER_SANITIZE_STRING);
        if($category !== false){
            if($category == "7"){//For Admin to edit product that is out of stock
                $filter= "sold_product = stock AND (name LIKE :keyword OR description LIKE :keyword)";
            }else{
                $filter= "category= '$category' AND (name LIKE :keyword OR description LIKE :keyword)";
            }
        }else{
            $filter= "(name LIKE :keyword OR description LIKE :keyword)";
        }
        $result= array();
        try{
            $stmt= $this->prepare("SELECT id, name, img, price, stock, sold_product FROM product WHERE $filter ORDER BY stock - sold_product DESC, name");
            $stmt->execute(array(
                ':keyword' => "%$keyword%"));
            //$keyword= $stmt->fetch(PDO::FETCH_ASSOC);
            while($t = $stmt->fetch(PDO::FETCH_ASSOC)){
                $result[]= $t;
            }
            $result= json_encode($result);
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
        }
        return $result;
    }

    public function get_product($id){
        try{
            $stmt= $this->prepare("SELECT * FROM product WHERE id= :id");
            $stmt->execute(array(
                ':id' => $id));
            $product= $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
            $product= false;
        }
        return $product;
    }

    public function edit_product($data, $suscriber){
        if(!isset($_SESSION['Admin'])){// Resrict access to Admin
            $_SESSION['info'] = "<div id='info'>Access denied.</div>";
            header("Location: "._DOMAIN_."/index.php");
            return;
        }
        $product_id= $data['product_id'];
        $product= $this->get_product($product_id);
        $noFile= $sameName= false;
        if(!isset($_FILES['product_img']) || $_FILES['product_img']['name']=="" ){// fake value to allow edit pass validation() for Edit
            $_FILES['product_img']['type']= 'image/jpeg';
            $_FILES['product_img']['size']= 1000;
            $noFile= true; 
        }
        if($data['product_name'] == $product['name']){//fake name if name not change for valication sake..
            $data['product_name']= $data['product_name'].rand(1000000, 10000000);
            $sameName= true;
        }
        $valid= $this->validate($data);
        if($valid){
            if( $this->if_exist($data['product_name']) == 0){
                //Restored all altered value for verification sake.
                $_FILES['product_img']['name']= $noFile ? $product['img'] : $_FILES['product_img']['name'];
                $data['product_name']= $sameName ? $product['name'] : $data['product_name'];

                //Update product information
                try{
                    $update= $this->prepare("UPDATE product SET name= :name, description= :desc, price= :price, stock= :qty, category= :cat, img= :img, sold_product= :qty_sold, delivery_class= :dc WHERE id='$product_id'");
                    $update->execute(array(
                        ':name' => $data['product_name'],
                        ':desc' => $data['product_desc'],
                        ':price' => $data['product_price'],
                        ':qty' => $data['product_qty'],
                        ':cat' => $data['category'],
                        ':img' => $_FILES['product_img']['name'],
                        'qty_sold' => $data['qty_sold'],
                        ':dc' => $data['delivery']
                    ));
                    if(!$noFile){//Save image if new one is uploaded
                        move_uploaded_file($_FILES['product_img']['tmp_name'], _ROOT_."/img/product/".$_FILES['product_img']['name']);
                        chown(_ROOT_."/img/product/$product[img]", _USER_);
                        unlink(_ROOT_."/img/product/$product[img]");
                    }
                    unset($_SESSION['p_name']); unset($_SESSION['qty_sold']); unset($_SESSION['p_desc']); unset($_SESSION['p_price']); unset($_SESSION['p_qty']); unset($_SESSION['p_cat']);

                    $suscriber->send_edit_mail($data);//Send mail to suscriber

                    $_SESSION['info'] = "<div id='info'>Product edited successfully.</div>";
                    header("Location: product.php?action=edit&id=$product_id");
                }catch(Exception $e){
                    error_log("Database(Admin) error  ::::". $e->getMessage());
                    $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
                    header("Location: product.php?action=edit&id=$product_id");
                }
            }else{
                $_SESSION['info'] = "<div id='info'>Product with name '($data[product_name])' already exist in the database.</div>";
                header("Location: product.php?action=edit&id=$product_id");
            }
        }else{
            $_SESSION['info'] = "<div id='info'>Invalid input.</div>";
            header("Location: product.php?action=edit&id=$product_id");
        }
    }

    public  function delete_product($id){
        if(!isset($_SESSION['Admin'])){// Resrict access to Admin
            $_SESSION['info'] = "<div id='info'>Access denied.</div>";
            header("Location: "._DOMAIN_."/index.php");
            return;
        }

        $product= $this->get_product($id);

        try{
            $stmt= $this->prepare("DELETE FROM product WHERE id= :id");
            $stmt->execute(array(
                ':id' => $id));
            chown(_ROOT_."/img/product/$product[img]", _USER_);
            unlink(_ROOT_."/img/product/$product[img]");//Delete show image
            $_SESSION['info'] = "<div id='info'>Product deleted.</div>";
            header('Location: product.php?action=product_mgt');
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
            header('Location: product.php?action=product_mgt');
        }
    }

    public function load_product($limit= 30, $off= 0, $category= false, $advance_filter= false){
        if($advance_filter){
            $category= ($category !== false) ? "WHERE (category= '$category') AND ($advance_filter)" : "WHERE $advance_filter";
        }else{
            $category= ($category !== false) ? "WHERE category= '$category'" : "";
        }
        try{
            $stmt= $this->query("SELECT id, name, img, price FROM product $category ORDER BY stock - sold_product DESC, id DESC LIMIT $limit OFFSET $off");
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
            $stmt= false;
        }
        return $stmt;
    }

    public function total_product($category= false, $advance_filter= false){
        if($advance_filter){
            $filter= ($category !== false) ? "WHERE (category= '$category') AND ($advance_filter)" : "WHERE $advance_filter";
        }else{
            $filter= ($category !== false) ? "WHERE category= '$category'" : "";
        }
        try{
            $stmt= $this->query("SELECT COUNT(*) FROM product $filter");
            $num= $stmt->fetchColumn();
        }catch(Exception $e){
            error_log("Database(Admin) error  ::::". $e->getMessage());
            $_SESSION['info'] = "<div id='info'>An error occurred.</div>";
            $num= false;
        }
        return $num;
    }
    
    
}