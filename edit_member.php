<?php
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';


// Sanitize if you want

//$member_id = $_GET['member_id'];
$member_id = filter_input(INPUT_GET, 'member_id', FILTER_SANITIZE_STRING);
$operation = filter_input(INPUT_GET, 'operation', FILTER_SANITIZE_STRING);
($operation == 'edit') ? $edit = true : $edit = false;

 $db = getDbInstance();

//Handle update request. As the form's action attribute is set to the same script, but 'POST' method,
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    //Get member id form query string parameter.
    $member_id = filter_input(INPUT_GET, 'member_id', FILTER_SANITIZE_STRING);
    echo $member_id;
    //Get input data
    $data_to_update['title'] = $_POST['title'];
    $data_to_update['surname'] = strtoupper($_POST['surname']);
    $data_to_update['othernames'] = strtoupper($_POST['othernames']);
    $data_to_update['dob'] = $_POST['dob'];
    $data_to_update['sex'] = $_POST['sex'];
    $data_to_update['blood_grp'] = $_POST['blood_grp'];
    $data_to_update['marital_status'] = $_POST['marital_status'];
    $data_to_update['name_spouse'] = $_POST['spousename'];
    $data_to_update['no_children'] = $_POST['childrennumber'];
    $data_to_update['State_origin'] = $_POST['state'];
    $data_to_update['town'] = $_POST['htown'];
    $data_to_update['lga'] = $_POST['lga'];
    $data_to_update['country'] = $_POST['country'];
    $data_to_update['home_addr'] = $_POST['address'];
    $data_to_update['off_addr'] = $_POST['offaddress'];
    $data_to_update['email'] = $_POST['email'];
    $data_to_update['mobile_no'] = $_POST['phone'];
    $data_to_update['degree_level'] = $_POST['quali'];
    $data_to_update['course_study'] = $_POST['coursestudied'];
    $data_to_update['occupation'] = $_POST['occupation'];
    $data_to_update['prof_qual'] = $_POST['proqual'];
    $data_to_update['current_band'] = $_POST['band'];
    $data_to_update['district'] = $_POST['districts'];
    $data_to_update['circuit'] = $_POST['circuits'];
    $data_to_update['branchname'] = $_POST['branches'];
    $data_to_update['post_held'] = $_POST['bandpost'];

    $data_to_update['updated_at'] = date('Y-m-d H:i:s');
    $db = getDbInstance();
    $db->where('memberid', $member_id);
    $stat = $db->update('tb_personinfo', $data_to_update);

    if($stat)
    {
        $_SESSION['success'] = "member updated successfully!";

        $db->where('id', $_SESSION['id']);
		$active_admin_user = $db->getOne('admin_accounts');

        $db->where('memberid', $member_id);
        
        $edited_member_info = $db->getOne('tb_personinfo');
        //Edit Member Successful. write and store activity in admin_activity db table
        $active_admin_names = $active_admin_user['surname'] . ' ' . $active_admin_user['firstname'] . '(' . $active_admin_user['user_name'] . ')';

        $edited_member_names = $edited_member_info['surname'] . ' ' . $edited_member_info['othernames'];

        $write_activity = $active_admin_names . ' edited Member with the name ' . $edited_member_names . '  on ' . date('D, M, d, Y h:i:s: A');

        $_SESSION['edit-member_activity'] = $write_activity;

        $user_data = array(
            'admin_id'     => $_SESSION['id'],
            'session_id'   => session_id(),
            'date'         => date('Y-m-d H:i:s'),
            'activity'     => $_SESSION['edit-member_activity'],
        );
        $returned_id = $db->insert('admin_activity', $user_data);

        //Store the activity information in a log file
        $file = 'logs/log.txt';
        if($handle = fopen($file, 'a')) {
            fwrite($handle, "\n" . $_SESSION['edit-member_activity']);
            fclose($handle);
        }

        //Write accomplished task to a log file and the database. Function definition found in helpers.php
        $data_to_update['admin_type'] = $_SESSION['admin_type'];
        save_general_admin_activity_to_log($data_to_update, "member", "edit", null, null, null, $edited_member_info);

        //Redirect to the listing page,
        header('location: members.php');
        //Important! Don't execute the rest put the exit/die.
        exit();
    }
}


//If edit variable is set, we are performing the update operation.
if($edit)
{
   // echo "Member Information will be loaded";
   // echo $member_id;
    $db->where('memberid', $member_id);
    //Get data to pre-populate the form.
    $member = $db->getOne("tb_personinfo");
}
?>


<?php
    include_once 'includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <h2 class="page-header">Update member</h2>
    </div>
    <!-- Flash messages -->
    <?php
        include('./includes/flash_messages.php')
    ?>

    <form class="" action="" method="post" enctype="multipart/form-data" id="editmember_form">

        <?php
            //Include the common form for add and edit
            require_once('./forms/member_form.php');
        ?>
    </form>
</div>




<?php include_once 'includes/footer.php'; ?>