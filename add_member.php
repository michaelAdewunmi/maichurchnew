<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';


//serve POST method, After successful insert, redirect to members.php page.
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{

    function generateMemberID()

    {

                $memid = mt_rand(20000, 99999);
                $NewMemberID = sprintf('%06d', $memid);
                $db = getDbInstance();
                $select = "memberid";
                $db->where ('memberid', $NewMemberID);
                $CheckMemberID = $db->get('tb_personinfo', null, $select);
                if ($db->count >=1) {
                    echo 'Record Dey';
                    generateMemberID();
                }
                else {
                    return $NewMemberID;
                }
    }
 

    //Mass Insert Data. Keep "name" attribute in html form same as column name in mysql table.
    //$data_to_store = array_filter($_POST);
    $data_to_store['memberid'] = generateMemberID();
    $data_to_store['title'] = $_POST['title'];
    $data_to_store['surname'] = strtoupper($_POST['surname']);
    $data_to_store['othernames'] = strtoupper($_POST['othernames']);
    $data_to_store['dob'] = $_POST['dob'];
    $data_to_store['sex'] = $_POST['sex'];
    $data_to_store['blood_grp'] = $_POST['blood_grp'];
    $data_to_store['marital_status'] = $_POST['marital_status'];
    $data_to_store['name_spouse'] = $_POST['spousename'];
    $data_to_store['no_children'] = $_POST['childrennumber'];
    $data_to_store['State_origin'] = $_POST['state'];
    $data_to_store['town'] = $_POST['htown'];
    $data_to_store['lga'] = $_POST['lga'];
    $data_to_store['country'] = $_POST['country'];
    $data_to_store['home_addr'] = $_POST['address'];
    $data_to_store['off_addr'] = $_POST['offaddress'];
    $data_to_store['email'] = $_POST['email'];
    $data_to_store['mobile_no'] = $_POST['phone'];
    $data_to_store['degree_level'] = $_POST['quali'];
    $data_to_store['course_study'] = $_POST['coursestudied'];
    $data_to_store['occupation'] = $_POST['occupation'];
    $data_to_store['prof_qual'] = $_POST['proqual'];
    $data_to_store['current_band'] = $_POST['band'];
    $data_to_store['district'] = $_POST['districts'];
    $data_to_store['circuit'] = $_POST['circuits'];
    $data_to_store['branchname'] = $_POST['branches'];
    $data_to_store['post_held'] = $_POST['bandpost'];
    //Insert timestamp-
    $data_to_store['DateCreated'] = date('Y-m-d H:i:s');
   //$data_to_store['user_name'] = $_SESSION['user_name']; <!--assign the user in the post-->
    $data_to_store['CreatedBy'] = $_SESSION['username'];
    
    $db = getDbInstance();
   // $user_name = $_SESSION['username'];

    $last_id = $db->insert('tb_personinfo', $data_to_store);
    //$last_id = $db->insert('members', $data_to_store);

    if ($last_id) {
        $_SESSION['success'] = "New Member added successfully!";
        $data_to_store['admin_type'] = $_SESSION['admin_type'];
        //Write accomplished task to a log file and the database. Function definition found in helpers.php
        save_general_admin_activity_to_log($data_to_store, "member", "add");

       header('location: add_member.php');
        exit();
    } else {
        echo 'insert failed: ' . $db->getLastError();
        exit();
    }
}

//We are using same form for adding and editing. This is a create form so declare $edit = false.
$edit = false;

require_once 'includes/header.php';
?>
<div id="page-wrapper">
<div class="row">
     <div class="col-lg-12">
            <h2 class="page-header">Add member</h2>
    </div>
   
</div>
<?php include('./includes/flash_messages.php') ?>
    <form class="form" action="" method="post"  id="member_form" enctype="multipart/form-data">
       <?php  include_once('./forms/member_form.php'); ?>
    </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
   $("#member_form").validate({
       rules: {
            f_name: {
                required: true,
                minlength: 3
            },
            l_name: {
                required: true,
                minlength: 3
            },
        }
    });
});
</script>
<script src="assets/js/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#country').on('change',function(){
        var countryName = $(this).val();
        if(countryName){
            $.ajax({
                type:'POST',
                url:'includes/classess.php',
                data:'CountryName='+countryName,
                success:function(html){
                    $('#state').html(html);
                    $('#lga').html('<option value="">Select LGA</option>'); 
                }
            }); 
        }else{
            $('#state').html('<option value="">Select Country First</option>');
            $('#lga').html('<option value="">Select State First</option>'); 
        }
    });
    
    $('#state').on('change',function(){
        var stateID = $(this).val();
        if(stateID){
            $.ajax({
                type:'POST',
                url:'includes/classess.php',
                data:'state_id='+stateID,
                success:function(html){
                    $('#lga').html(html);
                }
            }); 
        }else{
            $('#lga').html('<option value="">Select state first</option>'); 
        }
    });
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#districts').on('change',function(){
        var DistrictName = $(this).val();
        if(DistrictName){
            $.ajax({
                type:'POST',
                url:'includes/district_classess.php',
                data:'DistrictName='+DistrictName,
                success:function(html){
                    $('#circuits').html(html);
                    $('#branches').html('<option value="">Select Branch</option>'); 
                }
            }); 
        }else{
            $('#circuits').html('<option value="">Select District First</option>');
            $('#branches').html('<option value="">Select Circuit First</option>'); 
        }
    });
    
    $('#circuits').on('change',function(){
        var CircuitID = $(this).val();
        if(CircuitID){
            $.ajax({
                type:'POST',
                url:'includes/district_classess.php',
                data:'CircuitID='+CircuitID,
                success:function(html){
                    $('#branches').html(html);
                }
            }); 
        }else{
            $('#branches').html('<option value="">Select Circuit First</option>'); 
        }
    });
});
</script>

<?php include_once 'includes/footer.php'; ?>