<?php require_once('..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php') ?>
<?php
	$stock = new Stock(5);
	$output = "['Time', 'Profit'],";
	$arr = $output;
	$i = 0;
	foreach($stock->priceChanges as $price){
		$arr .="[".strtotime($price[1])." , ".$price[0]."],";
		$output .= "[".$i++." , ".$price[0]."],";
	}
	//echo $output;
?>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart1);

      function drawChart1() {
        var data = google.visualization.arrayToDataTable([
          <?php echo $output ;?>
        ]);

        var options = {
        	hAxis : {textPosition :'none'},
          title: 'Stock Performance',
          curveType: 'function',
         
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
    
  </head>
  <body>
 <div id="curve_chart" style="width: 1200px; height: 700px"></div>
  </body>
</html>