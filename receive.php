<?php
    $servername = "localhost";
    $username =/**/;
    $password = /**/;
    $dbname = "iot";


    #$handle = fopen("receive.txt", 'a');
    $string = $_POST["value"];
    
    $split = split(",", $string);


    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "INSERT INTO iot_data (time_stamp, moisture, light)
    VALUES ($split[0], $split[1], $split[2])";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    #fputs($handle,$string);
    
    #fputs($handle,$split[0]);
    #fputs($handle,"\n");
    #fputs($handle,$split[1]);
    #fputs($handle,"\n");
    #fputs($handle,$split[2]);
    #fputs($handle,"\n");
    
    #echo fpassthru($handle);

    $conn->close();
?>

