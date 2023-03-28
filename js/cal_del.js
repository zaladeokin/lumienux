function calc(){
    var products_id= $('#products_id').val();
    var qty= $('#products_qty').val();
    var state= $("#state").val();

    $.post( 'payment.php', { 'action' : 'evaluate', 'id' : products_id, 'qty' : qty, 'state' : state }, function( result ) {
        if(result != "0"){
            document.getElementById('delivery_fee').innerHTML= result;
            document.getElementById('tot_charges').innerHTML= parseInt(document.getElementById('cost').textContent) + parseInt(result);
        }else{
            document.getElementById('delivery_fee').innerHTML= "0";
            document.getElementById('tot_charges').innerHTML= document.getElementById('cost').textContent;  
        }  
    }).error( function() { 
      alert("Something went wrong.");
    });
}
$(document).ready(function(){
    $.ajaxSetup({cache: false});
    document.getElementById('state').addEventListener("change", calc);
    calc()
});