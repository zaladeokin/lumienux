function stat(page){
    $.post( 'config/stat.php', { 'page': page }, function( result ) {
        $('#stat').css('color', 'black');
        $('#stat').empty();
        $('#stat').append(result);
    }).error( function() { 
      $('#stat').css('color', 'red');
      $('#stat').empty().append("An error occur");
	});
}
