<?php
    $key=$_GET['key'];
    $array = array();
    $con=mysqli_connect("localhost","root","root@2017","admincore");
    $query=mysqli_query($con, "select * from localga where StateCode='{$key}')");
    while($row=mysqli_fetch_assoc($query))
    {
      $array[] = trim($row['LocalName']);
    }
    echo json_encode($array);
    mysqli_close($con);
?>