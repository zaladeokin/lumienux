function update(){
    var new_val, config_type, comp_value;

    var btn= this;

    config_type= this.dataset.type;

    if(btn.value== "Enable"){
        new_val = 1;
        comp_value= "Disable";// Value to be assign if request succeed
    }
    else{
        new_val= 0;
        comp_value= "Enable";
    }

    $.post( 'suscriber.php', { 'key' : config_type, 'value' : new_val }, function( result ) {
        if(result.status == "success") btn.value= comp_value;
        else alert("Unable to update settings, Try again.");
    }).error( function() { 
      alert("Something went wrong.");
    });

}

function insertSuscriber(data, count){
    if(document.querySelector('tbody') == null){
        document.querySelector('#suscribers').innerHTML= '<table class="table table-sm table-striped caption-top text-center"><caption>Suscribers</caption><thead class="table-dark"><tr><th scope="col">s/n</th> <th scope="col">Name</th> <th scope="col">Email</th></tr></thead><tbody></tbody></table></div></section>';
    }
    var tbody= document.querySelector('tbody');
    var tr= document.createElement('tr');

    var td1= tr.insertCell();
    td1.appendChild(document.createTextNode(count));
    var td2= tr.insertCell();
    td2.appendChild(document.createTextNode(data.name));
    var td3= tr.insertCell();
    td3.appendChild(document.createTextNode(data.email));

    tbody.appendChild(tr);
}


function hide_btn(){
    $('#suscribers').css('display', 'none');
    var main_btn= document.querySelector('main section button');
    main_btn.textContent= "View all suscriber";
    this.removeEventListener("click", hide_btn);
    main_btn.addEventListener('click', loadAll)
}

function loadAll(){
    var top_btn= this;
    $.getJSON('suscriber.php?view=all',function(result){
        $('#suscribers').empty();
        $('#suscribers').css('display', 'block');
        if(result.failed !== undefined){
            document.querySelector('#suscribers').innerHTML= "<p>"+result.failed+"</P>";
        }else if(result.length != 0){
            for(i=0; i < result.length; i++){
                var data= result[i];
                insertSuscriber(data, i + 1);
            }
        }else{
            document.querySelector('#suscribers').innerHTML= "<p>You don't have any suscriber on your list.</P>";
        }
        top_btn.textContent= "Hide";
        top_btn.removeEventListener("click", loadAll)
        top_btn.addEventListener("click", hide_btn);
        $('#suscribers').append('<button style="display:block;width:fit-content;margin:2vh auto;">Hide</button>');
        $('#suscribers button').click(hide_btn);
    });
    
}

$(document).ready(function(){
    $.ajaxSetup({cache: false});
    var settings= document.querySelectorAll('#settings input');
    settings[0].addEventListener("click", update);
    settings[1].addEventListener("click", update);
    document.querySelector('main section button').addEventListener('click', loadAll);
  });