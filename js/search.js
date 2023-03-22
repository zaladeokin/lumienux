function template(data){
    var available= data.stock - data.sold_product;
    if(available <= 0){
        available= "<span style='color: #ff0000;'>Out of stock</span>";
    }else if(available == 1){
        available= '1 stock available';
    }else{
        available= data.stock+' stocks available';
    }
    return '<div class="cart"><img src="../img/product/'+data.img+'" alt="'+data.name+'"><div><span>'+data.name+'</span><span>Price</strong>&nbsp;:&nbsp;'+data.price+'</span><span>'+available+'</span></div><div><a href="product.php?action=edit&id='+data.id+'"><button>Edit</button></a><a href="product.php?action=delete&id='+data.id+'"><button>Delete</button></a></div></div>';
}
function search(){
    $('#spinner').show();
    var keyword = $('#search').val();//Get search keyword
    var category= $('#category').val();//Search filter.
    if(category > 0){
        var param= { 'search': keyword, 'category': category }
    }else{
        var param= { 'search': keyword }
    }
    $.post( 'product.php', param, function( result ) {
        $('#search_outcome').empty();
        if(result.length != 0){
            for( var i= 0; i < result.length; i++){
                $('#search_outcome').append(template(result[i]));
            }
        }else{
            $('#search_outcome').append('<strong> No match found.</strong>');
        }
        $('#spinner').hide();
    }).error( function() { 
      $('#search_outcome').css('color', 'red');
      $('#search_outcome').empty().append("An error occur");
      $('#spinner').hide();
	});
}


document.getElementById('search').addEventListener("input", search);
document.getElementById('category').addEventListener("input", search);

  //Prevent file cache
  $(document).ready(function(){
    $.ajaxSetup({cache: false})
  });