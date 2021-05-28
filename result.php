<html>
	<body>
  <head>
		<link rel="stylesheet" type="text/css" href="style.css">

		<h1>SIEM SYSTEM </h1>
<hr>

<?php
$numRows= $_POST['numRows'];

	$filename = "kddcup.testdata.csv";
	$delimeter = ",";

	if(!file_exists($filename) || !is_readable($filename))
		return FALSE;

    $data = array();

	$numOfRows = 0;
	if (($handle = fopen($filename, 'r')) !== FALSE)
    {

        while (($row = fgetcsv($handle, 1024, $delimeter)) !== FALSE)
        {

			$data[$numOfRows] = $row;
			$numOfRows++;
        }
        fclose($handle);
    }


	echo"<table style=width:100%>";

	for ($row = 0; $row < $numRows; $row++) {
	echo"<tr>";
		for ($col = 0; $col < count($data[$row]); $col++) {

			echo "<th>"; echo $data[$row][$col] ; echo "</th>";
		}
	echo "</tr>";
	}
	echo"</table>";

	echo"<br>";
	echo"<br>";
	echo"<br>";

	?>
<hr>
	<div class="content">





<?php
	$createValue = $_POST['Create'];

	$newResult = array();
	$arrayErr = array();
	$numOfRows = 0;

	$columnToProcess = $createValue;

	foreach ($data as $dataRow)
    {
			$newResult[$numOfRows] = $dataRow[$createValue];
			$numOfRows++;
    }

	 $resultArray = array_count_values($newResult);
	 foreach ($resultArray as $amount) {
		if ($amount < 30)
		{
			array_push($arrayErr, "Yes");
		}
		else {
			array_push($arrayErr, "No");
		}

	}

	$row = 0;
	echo"<div>";
	echo"<table align=left>";
		echo"<tr>";
			echo "<th>"; echo "Services"; echo "</th>";

			echo "<th>"; echo "Amount" ; echo "</th>";
		echo "</tr>";

		 foreach ($resultArray as $segment) {

		echo"<tr>";
			echo "<th>"; echo key($resultArray) ; echo "</th>";


			echo "<th>"; echo ($segment) ; echo "</th>";


		echo "</tr>";
		next($resultArray);

	}
	echo"</table>";

	echo"<table>";
		echo"<tr>";


			echo "<th>"; echo "Issue" ; echo "</th>";
		echo "</tr>";

		 foreach ($arrayErr as $segment) {

		echo"<tr>";


			echo "<th>"; echo ($segment) ; echo "</th>";

		echo "</tr>";
		next($arrayErr);

	}
	echo"</table>";
echo"</div>";


	$dataTable = array(
    'cols' => array(
         array('type' => 'string', 'label' => 'Item'),
         array('type' => 'number', 'label' => 'Amount')
    )
	);


	reset($resultArray);
	while($segment = current($resultArray)) {

		$dataTable['rows'][] = array(
        'c' => array (
             array('v' =>  key($resultArray) ),
             array('v' =>  $segment)
         )
    );

		next($resultArray);
	}

	$json = json_encode($dataTable);
?>

<hr>
<div class="chart">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

google.load("visualization", "1", {packages: ["corechart", 'table']});


google.setOnLoadCallback(drawChart);

function drawChart() {
//
        var data = new google.visualization.DataTable(<?php echo $json; ?>);



    var options = {
        'title': 'Amount',
        'width': 600,
        'height': 600
    };

    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));

    function selectHandler() {
        var selectedItem = chart.getSelection()[0];
        var selectedItem2 = chart.getSelection()[1];
        if (selectedItem) {
            var topping = data.getValue(selectedItem.row, 0);
            var amount = data.getValue(selectedItem.row, 1);
        }
    }

    google.visualization.events.addListener(chart, 'select', selectHandler);
    chart.draw(data, options);
	}

    </script>
	</div>
  </head>

    <div id="chart_div" style="width: 900px; height: 600px"></div>
    <form action="index.php" method="POST">
    <input type="submit" name="backBtn" value="Back">
		</div>
    </form>
  </body>
</html>
