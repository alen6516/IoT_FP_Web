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

$sql = "SELECT * from iot_data order by time_stamp asc limit 10";
#$sql = "SELECT time_stamp, moisture, light FROM iot_data";
$result = $conn->query($sql);

$time_atamp = array();
$moisture = array();
$light = array();
$i = 0;

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "time:" . $row["time_stamp"]. " - moisture:" . $row["moisture"]. " - light:" . $row["light"]. "<br>";
        $time_stamp[$i] = $row['time_stamp'];
        $moisture[$i] = $row['moisture'];
        $light[$i++] = $row['light'];
    }
} else {
    echo "0 results";
}
/*
// note the value record in the array is in reverse order, so reverse it
$time_stamp = array_reverse($time_stamp);
$moisture = array_reverse($moisture);
$light = array_reverse($light);
*/

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
    <div id="container"></div>
    <div id="container2"></div>
</body>
</html>

<script>
$temp_moisture = <?php echo json_encode($moisture); ?>;
// the variable we receive is an array of String, we should transfer it to integer
$moisture = $temp_moisture.map(Number);

$temp_light = <?php echo json_encode($light); ?>;
$light = $temp_light.map(Number);

$temp_time = <?php echo json_encode($time_stamp); ?>;
$time = $temp_time.map(Number);

for ($i = 0; $i < $time.length; $i++)
{
    // transfer milli sec to min
    $time[$i]=parseInt($time[$i]/60000);
}

Highcharts.chart('container', {

    title: {
        text: 'Soil Moisture'
    },

    subtitle: {
        text: 'IoT-05'
    },

    xAxis: {
        title: {
            text: 'Time (min)'
        },
        categories: $time
    },
    yAxis: {
        title: {
            text: 'Moisture'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    /*plotOptions: {
        series: {
            pointStart: 2010
        }
    },*/

    series: [{
        name: 'Moisture',
        data: $moisture
    }]

});

Highcharts.chart('container2', {

    title: {
        text: 'Brightness'
    },

    subtitle: {
        text: 'IoT-05'
    },
    xAxis: {
        title: {
            text: 'Time (min)'
        },
        categories: $time
    },
    yAxis: {
        title: {
            text: 'Light'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    /*plotOptions: {
        series: {
            pointStart: 2010
        }
    },*/

    series: [{
        name: 'Light',
        data: $light
    }]

});
</script>
