function extractCookiePair(key){
    key= key+"=";
    var ck= document.cookie;
    var specific_cookie= false;
    if(ck != "" && key != "="){
        var cookie_in_arr= ck.split(";");//convert string to array
        for(let i=0; i<cookie_in_arr.length; i++){
            var fnd= cookie_in_arr[i].indexOf(key);
            if(fnd != -1){
                specific_cookie= cookie_in_arr[i].substr(key.length+1);
                break;
            }else{
                specific_cookie= "";
            }
        }
    }else{
        specific_cookie = "";
    }
    return specific_cookie;
}

function CartEva(strs, itm, act, global= false){
    if(strs != ""){
        var cartArray= strs.split(",");//convert string to array
        var ind= cartArray.indexOf(itm);//Find the index of item to remove
        if(act== 'delete' && ind != -1){
            var rmv= cartArray.splice(ind, 1);//Remove item based on its index if it exist
            if(!global){//var global is to prevent error when this function is call on page that doesn't contain querySelectorAll('#checkout....
                document.querySelectorAll('#checkout input')[1].value= 'add';// Change action status
                document.querySelector('#checkout button').innerHTML= '<i class="fa-solid fa-cart-plus"></i>Add to Cart';
            }
        }else if(act== 'add' && ind == -1){
            var add= cartArray.push(itm);//add item if not exist
            if(!global){
                document.querySelectorAll('#checkout input')[1].value= 'delete';//Change action status
                document.querySelector('#checkout button').innerHTML= '<i class="fa-solid fa-cart-plus"></i>Remove from Cart';
            }
        }
        return cartArray.toString();//Convert array back to string
    }else{
        return "";
    }
}
$(document).ready(function(){
    if(document.querySelectorAll('#checkout input')[0]){
        var product_id= document.querySelectorAll('#checkout input')[0].value;
        var action= document.querySelector('#checkout button');
        action.addEventListener("click", function(event){
            var product_status= document.querySelectorAll('#checkout input')[1].value;
            var cartCookie= extractCookiePair('cart');
            if(cartCookie != ""){//Check if cookie exist and alter it
                document.cookie= "cart="+CartEva(cartCookie, product_id, product_status)+"; path=/";
            }else{//create cookie if not exist
                document.cookie= "cart="+product_id+"; path=/";
                document.querySelectorAll('#checkout input')[1].value= 'delete';//Change action status
                document.querySelector('#checkout button').innerHTML= '<i class="fa-solid fa-cart-plus"></i>Remove from Cart';
            }
        });
    }
  });