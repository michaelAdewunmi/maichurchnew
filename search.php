<?php
    $key=$_GET['key'];
    $array = array();
    $con=mysqli_connect("localhost","root","root@2017","admincore");
    $query=mysqli_query($con, "select * from tb_personinfo where (surname LIKE '%{$key}%' or othernames LIKE '%{$key}%' or memberno LIKE '%{$key}%')");
    while($row=mysqli_fetch_assoc($query))
    {
      $array[] = trim($row['memberno']).' '
                  .trim($row['surname']).' '
                  .trim($row['othernames']).' '
                  .trim($row['mobile_no']).' '
                  .str_replace(" ", "_", trim($row['current_band'])).' '
                  .str_replace(" ", "_", trim($row['branchname']));

    }
    echo json_encode($array);
    mysqli_close($con);
?>

<?php
    // $key=$_GET['key'];
    // $array = array();
    // $con=mysqli_connect("localhost","root","root@2017","admincore");
    // $query=mysqli_query($con, "select * from tb_personinfo where (surname LIKE '%{$key}%' or othernames LIKE '%{$key}%' or memberno LIKE '%{$key}%')");
    // while($row=mysqli_fetch_assoc($query))
    // {
    //   $array[] = trim($row['memberno']).' '.trim($row['surname']).' '.trim($row['othernames']).' '.str_replace(" ","_",trim($row['current_band'])).' '.trim($row['branchname']);
    // }
    // echo json_encode($array);
    // mysqli_close($con);
?>