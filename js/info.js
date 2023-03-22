function get_info(id){
    var card= document.getElementById('order_hide');
    card.setAttribute('id', 'order_info');

    $.post( 'order.php', {'order_id' : id }, function( result ) {
        $('#order_info').empty();
        $('#order_info').append(result);
        document.querySelector('#order_info a button').addEventListener('click', exist_info);
        document.querySelector('#order_info > button').addEventListener('click', action);    
    }).error( function() { 
      $('#order_info').css('color', 'red');
      $('#order_info').empty().append("Something went wrong<br>Try again....");
	});
}

function exist_info(){
    var card= document.getElementById('order_info');
    card.setAttribute('id', 'order_hide');
}

function action(){
    $.post( 'order.php', {'active_id' : this.dataset.id, 'action' : this.dataset.action }, function( result ) {
        if(result.message != ""){
            $('#order_info p span').empty().append(result.message);
            document.querySelector('#order_info > button').remove();
        } 
    }).error( function() { 
      $('#order_info p span').css('color', 'red');
      $('#order_info p span').empty().append(" Something went wrong");
	});
}


//Attach events here
$(document).ready(function(){
    var action_btn= document.getElementsByClassName('view_order');
    for(i=0; i < action_btn.length; i++){
        action_btn[i].addEventListener("click", function(){
            get_info(this.dataset.id);
        });
    }
});