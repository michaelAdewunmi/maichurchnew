<?php
require_once '../config/config.php'; 
if(!empty($_POST['CountryName']))
{
    $db = getDbInstance();
    $select = "StateName";
    $db->where("CountryName", $_POST['CountryName']);
    $opt_arr = $db->get('state_tbl', null, $select);
    
    if(!empty($opt_arr))
    {
        echo '<option value="">Select State</option>';
        foreach ($opt_arr as $states){
        //echo '<option value="'.$states['StateName'].'"''>' . $states['StateName'] . '</option>';
        echo '<option value="'.$states['StateName'].'">'.$states['StateName'].'</option>';
        }
    }
    else{
        echo '<option value="">State not available</option>';
        
        }
}
elseif(!empty($_POST['state_id']))
{
    $db = getDbInstance();
    $select = "LocalName";
    $db->where("StateCode", $_POST['state_id']);
    $opt_arr = $db->get('localga', null, $select);
    
    if(!empty($opt_arr))
    {
        echo '<option value="">Select LGA</option>';
        foreach ($opt_arr as $lgas){
        //echo '<option value="'.$states['StateName'].'"''>' . $states['StateName'] . '</option>';
        echo '<option value="'.$lgas['LocalName'].'">'.$lgas['LocalName'].'</option>';
        }
    }
    else{
        echo '<option value="">LGAs not available</option>';
        
        }
}
elseif(!empty($_POST['DistrictName']))
        {
           $circuit = $db->get('districtciruitbranches',null,'distinct circuit');;
            
            if(!empty($opt_arr))
            {
                echo '<option value="">Select Circuit</option>';
                foreach ($opt_arr as $circuit){
                //echo '<option value="'.$states['StateName'].'"''>' . $states['StateName'] . '</option>';
                echo '<option value="'.$circuit['circuit'].'">'.$circuit['circuit'].'</option>';
                }
            }
            else{
                echo '<option value="">Circuit not available</option>';
                
                }


        }

?>