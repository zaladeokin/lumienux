function updateCart(total=0){
    var num_tb= document.getElementsByClassName('cart').length;
    if(num_tb == 0){//Check if cart is not empty
        document.getElementById('cart_summary').innerHTML='<div>Your Cart is empty.<br><br><a href="product.html">Click here to view our products</a></div>';
    }else{
        document.getElementById('total').innerHTML= total;
    }
}

function updateSummary(){
    var new_total= 0;//To recalculate grand total
    var i=0;//table index(s/n)
    document.querySelector('#cart_summary tbody').innerHTML= "";//Delete all summary record
    for(const key in btn_properties){//recreate summary table
        i= i + 1;
        var obj= btn_properties[key]
        insertOrder(i, obj[5], obj[2], obj[3],obj[4], obj[1]);
        new_total= new_total + obj[4];//new grand total
    }
    updateCart(new_total);

}


function insertOrder(a, b, c, d, e, id){
    var tbody= document.querySelector('#cart_summary tbody');
    var tr= document.createElement('tr');
    tr.setAttribute('id', 'sum_'+id)

    var td1= tr.insertCell();
    td1.appendChild(document.createTextNode(a));
    var td2= tr.insertCell();
    td2.appendChild(document.createTextNode(b));
    var td3= tr.insertCell();
    td3.appendChild(document.createTextNode(c));
    var td4= tr.insertCell();
    td4.appendChild(document.createTextNode(d));
    var td5= tr.insertCell();
    td5.appendChild(document.createTextNode(e));

    tbody.appendChild(tr);
}


var carts_btn= document.querySelectorAll('.cart button');// get alll .cart remove button
var product_names= document.querySelectorAll('.cart span:first-child');//get product name of each .cart
var btn_properties= new Array();//store .cart details
var total=0;//total amount of oder
for(i=0; i<carts_btn.length; i++){
    var btn= carts_btn[i]; var id= i+1;    
    var qty_input= document.getElementById('p_qty_'+id);//get order of cart
    var pname= product_names[i].innerHTML;// product name
    var price= document.getElementById('p_price_'+id).value;
    var qty= qty_input.value;
    var p_id= document.getElementById('p_id_'+id).value;
    var amt= qty * price;//calculate each cart amount
    btn_properties[btn.closest(".cart").id]= ["sum_"+id, p_id, qty, price, amt, pname];
    insertOrder(id, pname, qty, price, amt, p_id);
    total= total + (amt);//grand total of cart

    //Add event listener to remove button
    btn.addEventListener("click", function(event){
        delete btn_properties[this.closest(".cart").id];//remove deleted car from array storage
        document.getElementById(this.closest(".cart").id).remove();//delete cart
        updateSummary();//update summary table
    });

    //add event listener to input box
    qty_input.addEventListener("change", function(event){
        var new_value= this.value;
        var new_amt= new_value * btn_properties[this.closest(".cart").id][3];
        btn_properties[this.closest(".cart").id][2]= new_value;//update quantity
        btn_properties[this.closest(".cart").id][4]= new_amt; //update amount
        updateSummary();//update summary table
    });
}
updateCart(total);