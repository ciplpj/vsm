<?php //if(!isset($_SESSION['admin'])) 
//header('Location: index.php');

?>
<!DOCTYPE html>
<html class="not-ie" lang="en"> <!--<![endif]-->
<head>
	<title>Update | Virtual Stock market | E-Summit 2015</title>
	
	<style>
	.stock_sel{
width:70px !important;
}
#kl{
display:none;
}
	</style>
</head>
<body>

<script src="../js/jquery-1.7.2.min.js"></script>
<script>

function rand_n (min, max) {
    var res =  Math.random() * (max - min) + min;
	var item = Math.floor(Math.random()*2);
if(item==0) res = -(res);

	return res.toFixed(1);
}

function rand_f(param)
{
var res = (Math.random() * (param + 1)).toFixed(1);

return res;
}


function update_price(count)
{
var b1 = rand_n(0,$('#box1').val());
var b2 = rand_n(0,$('#box2').val());
var b3 = rand_n(0,$('#box3').val());
var b4 = rand_n(0,$('#box4').val());
var b5 = rand_n(0,$('#box5').val());
var b6 = rand_n(0,$('#box6').val());
var b7 = rand_n(0,$('#box7').val());
var b8 = rand_n(0,$('#box8').val());
var b9 = rand_n(0,$('#box9').val());
var b10 = rand_n(0,$('#box10').val());


var data = {"st1": b1 , "st2": b2 , "st3": b3 , "st4": b4 , "st5": b5 , "st6": b6 , "st7": b7 , "st8": b8 , "st9": b9 , "st10": b10 };

console.log(data);

$.ajax({
            type: "POST",
            url: "upd.php?count="+count+"",
            data: data,
            dataType: "json",
            success: function (result) {
			console.log(result);
            },
			 error: function (xhr) {
             console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
			}
        });
		
		
}

var count = 0;
setInterval(function(){ 
var currentdate = new Date(); 

update_price(count);
count++;
document.getElementById("time").innerHTML = currentdate + count;

}, 3000);

</script>
<h3>Enter values</h3>

<div id="time"></div>
<br/><br/>
Stock 1: <input id="box1" type="text" value="3" class="hello" /><br/><br/>
Stock 2: <input id="box2" type="text" value="3" class="hello" /><br/><br/>
Stock 3: <input id="box3" type="text" value="3" class="hello" /><br/><br/>
Stock 4: <input id="box4" type="text" value="3" class="hello" /><br/><br/>
Stock 5: <input id="box5" type="text" value="3" class="hello" /><br/><br/>
Stock 6: <input id="box6" type="text" value="3" class="hello" /><br/><br/>
Stock 7: <input id="box7" type="text" value="3" class="hello" /><br/><br/>
Stock 8: <input id="box8" type="text" value="3" class="hello" /><br/><br/>
Stock 9: <input id="box9" type="text" value="3" class="hello" /><br/><br/>
Stock 10: <input id="box10" type="text" value="3"  class="hello" />
	
	
</body>
</html>		






