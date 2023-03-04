function template(data){
    return '<figure><a href="checkout.php?id='+data.id+'"><img src="img/product/'+data.img+'" alt="'+data.name+'"><figcaption>'+data.name+'</figcaption></a></figure>';

}
var initial_content= document.querySelector('main').innerHTML;
function search(){
    var keyword = $('#search').val();//Get search keyword
    $.post( 'product.php', { 'search': keyword }, function( result ) {
        $('main').empty();
        if(result.length != 0){
            for( var i= 0; i < result.length; i++){
                $('main').append(template(result[i]));
            }
        }else{
            $('main').append('<strong> No match found.</strong>');
        }
    }).error( function() { 
      $('main').css('color', 'red');
      $('main').empty().append("An error occur");
	});
}


document.getElementById('search').addEventListener("input", search);
document.getElementById('search_btn').addEventListener("click", function(event){
    event.preventDefault();
    if($('#search').val() != ""){
        search();
    }
});
document.getElementById('search').addEventListener("blur", function(event){
    if($('#search').val() == ""){
        document.querySelector('main').innerHTML= initial_content;
    }
});

  //Prevent file cache
  $(document).ready(function(){
    $.ajaxSetup({cache: false})
  });