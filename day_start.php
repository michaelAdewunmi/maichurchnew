<?php
$d = date('Y-m-d');
$db = getDbInstance();
$db->where("day", date('Y-m-d'));
$db->where("day_started", true);
$db->where("day_started_for", $_SESSION['username']);
$db->where("day_ended", "NOT YET");
$db->where("time_day_ended", "NULL");
$row = $db->get('start_and_end_day_controller');
if ($db->count>0) {
    setSessionAndGoToIndexPage();
} else {
    $db = getDbInstance();
    $db->where("day", date('Y-m-d'));
    $db->where("day_started_for", $_SESSION['username']);
    $db->where("day_ended", "1");
    $row = $db->get('start_and_end_day_controller');
    if ($db->count>0) {
        setSessionAndGoToIndexPage(true);
    } else {
        $db = getDbInstance();
        $db->where("day", date('Y-m-d'));
        $row = $db->get('start_and_end_day_controller');
        if($db->count>0) {
            $day_id = $row[0]["day_id"];
            saveInfoToStartDayController($day_id);
        } else {
            $db = getDbInstance();
            $db->where("day", date('Y-m-d', strtotime("-1 days")));
            $db->where("day_started_for", $_SESSION['username']);
            $the_row = $db->get('start_and_end_day_controller');
            if ($db->count>0) {
                end_the_prev_days($the_row); //If Previous day wasnt ended
                setNewDayIdFromPrevDayAndStartNewDay($the_row[0]);
            } else {
                $db = getDbInstance();
                $db->where("day", date('Y-m-d', strtotime("-1 days")));
                $the_row = $db->get('start_and_end_day_controller');
                if ($db->count>0) {
                    $db = getDbInstance();
                    $db->where("day_started_for", $_SESSION['username']);
                    $row = $db->get('start_and_end_day_controller');
                    if ($db->count>0) {
                        end_the_prev_days($row);
                    }
                    setNewDayIdFromPrevDayAndStartNewDay($the_row[0]);
                } else {
                    $db = getDbInstance();
                    $db->where("day_started_for", $_SESSION['username']);
                    $row = $db->get('start_and_end_day_controller');
                    if ($db->count<1) {
                        $db = getDbInstance();
                        $db->where("id>0");
                        $another_row = $db->get('start_and_end_day_controller');
                        if ($db->count<1) {
                            $result = saveInfoToStartDayController("SL/FIN/".$d."/00000001");
                            if ($result!==null) {
                                setSessionAndGoToIndexPage();
                            } else {
                                header("Location: problemPage.php");
                            }
                        } else {
                            $recent_day = end($another_row);
                            setNewDayIdFromPrevDayAndStartNewDay($recent_day);
                        }

                    } else {
                        end_the_prev_days($row);
                        $recent_day = end($row);
                        setNewDayIdFromPrevDayAndStartNewDay($recent_day);
                    }
                }
            }
        }

    }
}

function setNewDayIdFromPrevDayAndStartNewDay($the_row) {
    $d = date('Y-m-d');
    $previous_serial_num = intval(substr($the_row["day_id"], 18));
    $day_id = "SL/FIN/".$d."/".sprintf("%08d", $previous_serial_num+1);
    $result = saveInfoToStartDayController($day_id);
    if ($result!==null) {
        setSessionAndGoToIndexPage();
    } else {
        header("Location: problemPage.php");
    }
}

function end_the_prev_days($rows) {
    foreach($rows as $row) {
        $db = getDbInstance();
        $db->where("id", $row["id"]);
        if ($row["day_ended"]==="NOT YET") {
            $data = Array (
                "day_ended"         => true,
                "day_ended_for"     => $_SESSION['username'],
                "time_day_ended"    => date('Y-m-d H:i:s'),
            );
            $result = $db->update("start_and_end_day_controller", $data);
        }
    }

}

/*
CREATE TABLE `start_and_end_day_controller` (
    `id` INT(50) NOT NULL AUTO_INCREMENT,
    `day` DATE NOT NULL,
    `day_started` TINYINT(1) NOT NULL DEFAULT 0,
    `time_day_started` VARCHAR(50) DEFAULT NULL,
    `day_ended` VARCHAR(7) DEFAULT 'NOT YET',
    `time_day_ended` VARCHAR(50) DEFAULT 'NULL',
    `endorsed_by` VARCHAR(10) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `cashiers_login_tokens` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `token` BIGINT NOT NULL,
    `raw_date` BIGINT NOT NULL,
    `nice_date` TIMESTAMP NOT NULL,
    `created_for` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    */

?>




