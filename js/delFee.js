function insertState(data, count){
    if(document.querySelector('tbody') == null){
        document.querySelector('main section > div').innerHTML= '<table class="table table-sm table-striped caption-top text-center"><caption>Delivery fee chart</caption><thead class="table-dark"><tr><th scope="col">s/n</th> <th scope="col">State</th> <th scope="col">Light-weight</th> <th scope="col">Medium-weight</th><th scope="col">High-weight</th><th scope="col"></th><th scope="col"></th></tr></thead><tbody></tbody></table></div></section>';
    }
    var tbody= document.querySelector('tbody');
    var tr= document.createElement('tr');
    tr.setAttribute('data-id', data.id)

    var td1= tr.insertCell();
    td1.appendChild(document.createTextNode(count));
    var td2= tr.insertCell();
    td2.appendChild(document.createTextNode(data.state));
    var td3= tr.insertCell();
    td3.appendChild(document.createTextNode(data.light));
    var td4= tr.insertCell();
    td4.appendChild(document.createTextNode(data.medium));
    var td5= tr.insertCell();
    td5.appendChild(document.createTextNode(data.high));
    var td6= tr.insertCell();
    var btn1= document.createElement('button');
    btn1.setAttribute('id', 'edit_'+count);
    btn1.appendChild(document.createTextNode('Edit'));
    btn1.addEventListener("click", edit_state_temp);//Add event listener to button
    td6.appendChild(btn1);
    var td7= tr.insertCell();
    var btn2= document.createElement('button');
    btn2.setAttribute('id', 'delete_'+count);
    btn2.appendChild(document.createTextNode('Delete'));
    btn2.addEventListener("click",  delete_state);//Add event listener to button
    td7.appendChild(btn2);

    tbody.appendChild(tr);
}

function edit_state_temp(){
    var tr=this.closest('tr');
    var state_id= tr.dataset.id;
    var fields= $('tr[data-id="'+state_id+'"] td');

    tr.innerHTML='';

    var td1= tr.insertCell();
    td1.appendChild(fields[0]);
    var td2= tr.insertCell();
    var st_inp= document.createElement('input');
    st_inp.type= 'text';
    st_inp.value= fields[1].textContent;
    td2.appendChild(st_inp);
    var td3= tr.insertCell();
    var lt_inp= document.createElement('input');
    lt_inp.type= 'number';
    lt_inp.value= fields[2].textContent;
    td3.appendChild(lt_inp);
    var td4= tr.insertCell();
    var md_inp= document.createElement('input');
    md_inp.type= 'number';
    md_inp.value= fields[3].textContent;
    td4.appendChild(md_inp);
    var td5= tr.insertCell();
    var hi_inp= document.createElement('input');
    hi_inp.type= 'number';
    hi_inp.value= fields[4].textContent;
    td5.append(hi_inp);
    var td6= tr.insertCell();
    var btn1= document.createElement('button');
    btn1.setAttribute('id', 'edit_'+state_id);
    btn1.appendChild(document.createTextNode('Ok'));
    btn1.addEventListener("click", edit_state);//Add event listener to button
    td6.appendChild(btn1);
    var td7= tr.insertCell();
    var btn2= document.createElement('button');
    btn2.setAttribute('id', 'delete_'+state_id);
    btn2.appendChild(document.createTextNode('Cancel'));
    btn2.addEventListener("click",  refresh);//Add event listener to button
    td7.appendChild(btn2);
}

function edit_state(){
    this.disabled= true;//Prevent repeated request... Button enable at the end of function
    var state_id= this.closest('tr').dataset.id;
    var inps= $('tr[data-id="'+state_id+'"] input');

    if(inps[0].value !== "" && inps[1].value !==  "" && inps[2].value !== "" && inps[3].value !== ""){
        $.post( 'deliveryfee.php', {'action' : 'edit', 'id' : state_id, 'state' : inps[0].value, 'light' : inps[1].value, 'medium' : inps[2].value, 'high' : inps[3].value }, function( result ) {
            if(result.status== "success"){
                refresh();//Reload table for update
                alert("Success");
            }else if(result.status== "failed"){
                alert(result.msg);
            }
        }).error( function() {
          alert("An error occurred");
        });
    }else{
        alert("Invalid Input.");
    }
    this.disabled= false;
}

function add_state(btn){
    btn.disabled= true;//Prevent repeated request... Button enable at the end of function
    var status= $('#add span');

    var state= $('#add #state');
    var light= $('#add #light');
    var medium= $('#add #medium');
    var high= $('#add #high');
    if(state.val() !== "" && light.val() !==  "" && medium.val() !== "" && high.val() !== ""){
        $.post( 'deliveryfee.php', {'action' : 'add', 'state' : state.val(), 'light' : light.val(), 'medium' : medium.val(), 'high' : high.val() }, function( result ) {
            if(result.status== "success"){
                refresh();
                status.css('color', 'green');
                status.empty().append(state.val()+" successfully added.");
                state.val('');
                light.val('');  
                medium.val(''); 
                high.val('');
            }else if(result.status== "failed"){
                status.css('color', 'red');
                if(result.msg==""){
                    status.empty().append("Failed, Try again.");
                }else{
                    status.empty().append(result.msg);
                }
            }   
        }).error( function() { 
          status.css('color', 'red');
          status.empty().append("Something went wrong.");
        });
    }else{
        status.css('color', 'red');
        status.empty().append("Invalid Input.");
    }
    btn.disabled= false;
}

function delete_state(){
    var state_id= this.closest('tr').dataset.id;
    var fields= $('tr[data-id="'+state_id+'"] td');
    var confirmation= confirm("Are you sure you to Delete "+ fields[1].textContent);
    if(confirmation){
        $.post( 'deliveryfee.php', { 'action' : 'delete', 'id' : state_id }, function( result ) {
            if(result.status== "success"){
                refresh();
                alert("Success");
            }else if(result.status== "failed"){
                alert(result.msg);
            }   
        }).error( function() { 
          alert("Something went wrong.");
        });
    }
}

function refresh(){
    $.getJSON('deliveryfee.php?load', function(result){
        $('main section > div').empty();
        if(result.length != 0){
            for(i=0; i < result.length; i++){
                var data= result[i];
                insertState(data, i + 1);
            }
        }else{
            document.querySelector('main section > div').innerHTML= "<p>Delivery fee have not been fix for any state.</P></div></section>";
        }
    })
}

//Attach events here
$(document).ready(function(){
    $.ajaxSetup({cache: false});
    var add_btn= document.querySelector('#add input[type="submit"]');
    add_btn.addEventListener("click", function(event){
        event.preventDefault();
        add_state(this);
    });
    refresh();
});