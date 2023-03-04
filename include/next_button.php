<?php
if($pagination['next'] > 0){
?>
    <button class="pagination">Load more</button>
<?php } ?>
<script>
$(document).ready(function(){
    $('.pagination').click(function(event){
        event.preventDefault();
        $.post( '<?= _CURRENT_FILE_; ?>', { 'page': <?= $pagination['next']; ?> }, function( result ) {
            $('.pagination').remove();
            $('.product').append( result );
        }).error( function() { 
            $('product').append("<div class='info'>An error occur</div>");
	    });
    });
});

</script>