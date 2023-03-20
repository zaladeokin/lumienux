if(document.querySelectorAll("#payment input").length != 0){
    var products_fields= document.querySelectorAll("#payment input");
    products_fields[3].addEventListener("click", function(event){
        event.preventDefault();
        var products_info= document.querySelectorAll("#cart_summary tbody tr");
        var id_array= document.querySelectorAll(".cart .product_id");
        var products_ids= new Array()
        var products_name= new Array();
        var products_qty= new Array();
        for(i=0; i<products_info.length; i++){
            products_ids.push(id_array[i].value);
            products_name.push(products_info[i].childNodes[1].innerHTML);
            products_qty.push(products_info[i].childNodes[2].innerHTML);
        }
        var total_amt= document.querySelector("#cart_summary tfoot tr").childNodes[1].innerHTML;
        products_fields[0].value= JSON.stringify(products_ids);
        products_fields[1].value= JSON.stringify(products_qty);
        products_fields[2].value= JSON.stringify(products_name);
        document.getElementById('payment').submit();
    });
}