<?php
require_once '../config/config.php'; 
if(!empty($_POST['DistrictName']))
        {
            echo $_POST['DistrictName'];
            $db = getDbInstance();
            $db->where ('District', $_POST['DistrictName']);
            $circuit = $db->get('districtciruitbranches',null,'distinct Circuit');
            
            if(!empty($circuit))
            {
                echo '<option value="">Select Circuit</option>';
                foreach ($circuit as $circuits){
                //echo '<option value="'.$states['StateName'].'"''>' . $states['StateName'] . '</option>';
                echo '<option value="'.$circuits['Circuit'].'">'.$circuits['Circuit'].'</option>';
                }
            }
            else{
                echo '<option value="">Circuit not available</option>';
                
                }


        }
elseif(!empty($_POST['CircuitID']))
{
    $db = getDbInstance();
    $select = "Branch";
    $db->where("Circuit", $_POST['CircuitID']);
    $opt_arr = $db->get('districtciruitbranches', null, $select);
    
    if(!empty($opt_arr))
    {
        echo '<option value="">Select Circuit</option>';
        foreach ($opt_arr as $branches){
        //echo '<option value="'.$states['StateName'].'"''>' . $states['StateName'] . '</option>';
        echo '<option value="'.$branches['Branch'].'">'.$branches['Branch'].'</option>';
        }
    }
    else{
        echo '<option value="">Branch not available</option>';
        
        }


}
?>