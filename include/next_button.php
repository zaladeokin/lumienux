<?php
if($pagination['next'] > 0){
?>
    <button class="pagination">Load more</button>
<?php } ?>
<script>
$(document).ready(function(){
    $('.pagination').click(function(event){
        event.preventDefault();
        var category;//Get category value in hidden input
        if(document.getElementById('post_category') != null){
            category= { 'page': <?= $pagination['next']; ?>, 'category': document.getElementById('post_category').value}
        }else{
            category= { 'page': <?= $pagination['next']; ?> };
        }
        $.post( '<?= _CURRENT_FILE_; ?>', category, function( result ) {
            $('.pagination').remove();
            $('.product').append( result );
        }).error( function() { 
            $('product').append("<div class='info'>An error occur</div>");
	    });
    });
});

</script>