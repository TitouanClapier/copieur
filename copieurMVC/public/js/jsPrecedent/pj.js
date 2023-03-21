counter = 0;
$(".ajout-pj").click(function(){
    counter++;
    $("#pj-list").append(" <li  id=file"+counter+"><input type='file' class='form-control-file' name='file[]'></li> ");

});

$(".supp-pj").click(function(){
    console.log(counter);
    $("#file"+counter).remove();
    counter--;
});

function suppPj(idPj)
{
    if(window.XMLHttpRequest)
        xhr = new XMLHttpRequest();
    else if(window.ActiveXObject){ // Internet Explorer
        try {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }}

    xhr.open('POST', '../public/index.php?route=pj', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("idPj="+idPj);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            $('#' + idPj).remove();
        }
    }

}