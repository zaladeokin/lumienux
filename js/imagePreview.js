function preview(){
    //Read file
    var img= document.getElementById('image');
    const files= img.files;
    if(!files || files.length == 0) return;
    const file= files[0];
    const r_file= new FileReader();
    r_file.readAsDataURL(file);
    r_file.onload= ()=>{
        //Create img andset attribute
        var pre= document.createElement('img');
        pre.setAttribute('src', r_file.result);
        pre.setAttribute('width', '50%');
        document.getElementById('preview').innerHTML="";
        document.getElementById('preview').appendChild(pre);
    }
}
//Add event listener to button
if(document.getElementById('image') != null) document.getElementById('image').addEventListener("change", preview);