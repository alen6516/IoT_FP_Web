<?php
$servername = "localhost";
$username = /**/;
$password = /**/;
$dbname = "iot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// select data from the latest --> oldest
$sql = "SELECT * from iot_data order by time_stamp desc limit 10";
$result = $conn->query($sql);

$time_stamp = array();
$moisture = array();
$light = array();
$i = 0;

if ($result->num_rows > 0) {
    // output data of each row
    // select the last 10 data
    while($row = $result->fetch_assoc()) {
        echo "time:" . $row["time_stamp"]. " - moisture:" . $row["moisture"]. " - light:" . $row["light"]. "<br>";
        $time_stamp[$i] = $row['time_stamp'];
        $moisture[$i] = $row['moisture'];
        $light[$i++] = $row['light'];
    }
} else {
    echo "0 results";
}


// note the value record in the array is in reverse order, so reverse it 
$time_stamp = array_reverse($time_stamp);
$moisture = array_reverse($moisture);
$light = array_reverse($light);


$conn->close();
?>

<html>
<head>
    <link rel="stylesheet" href="css/chartStyle.css">

    <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
    <!--<script src="js/chartController.js"></script>-->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
</head>
<body>

    <p>the LED will turn on when Brightness value &lt; 8</p>
    <p>the Moter will turn on when Moisture value &lt; 5</p>
    <div id="container">
    </div>
</body>
</html>

<script>
//var time_stamp = <?php echo json_encode($time_stamp); ?>;
//var moisture = <?php echo json_encode($moisture); ?>;
//var light = <?php echo json_encode($light); ?>;

$temp_moisture = <?php echo json_encode($moisture); ?>;
// the variable we receive is an array of String, we should transfer it to integer
$moisture = $temp_moisture.map(Number);

$temp_light = <?php echo json_encode($light); ?>;
$light = $temp_light.map(Number);

$temp_time = <?php echo json_encode($time_stamp); ?>;
$time = $temp_time.map(Number);

$i = 0;
for ($i = 0; $i < $time.length; $i++)
{
    // transfer milli sec to min
    $time[$i]=parseInt($time[$i]/60000);
    $moisture[$i]=10-($moisture[$i]/100);
    $light[$i]=10-($light[$i]/100);
}



$(function () {
    Highcharts.chart('container', {

    title: {
        text: 'Moisture and Brightness vary with time'
    },

    subtitle: {
        text: 'IoT-05'
    },

    yAxis: {
        title: {
            text: 'Sensor data value'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    /*
    plotOptions: {
        series: {
            pointStart: 2010
        }
    },
    */
    xAxis: {
        title: {
            text: 'Time (min)'
        },
        categories: $time
    },

    series: [{
        name: 'Moisture',
        data: $moisture
    },{
        name: 'Brightness',
        data: $light
    }]

});
});

</script>
