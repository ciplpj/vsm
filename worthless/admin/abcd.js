
setInterval(function(){ 
var key = $('#unique').val();
var user = $('#user').val();

console.log(user +"--" + key);

$.ajax({
            type: "POST",
            url: "index.php",
            data: {"user":user , "key":key},
           dataType: 'JSON',
		   cache: false,
            success: function (data) {
			//
			if(data == 0) window.location = "index.php";
			else window.location = "update.php";
            },
			 error: function (xhr) {
             console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
			}
        });
		
}, 10000);