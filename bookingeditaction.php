<?php
include('config.php');
session_start();

// Define constants for repeated HTML segments
define('SUCCESS_HTML', '
    <div style="text-align: center;">
        <a href="bookingadmin.php" style="display: inline-block; padding: 10px 20px; text-align: center; text-decoration: none; background-color: #00ff00; border-radius: 5px; margin-right: 10px;">View</a>
    </div>
');

define('FAILED_HTML', '
    <div style="text-align: center;">
        <a href="javascript:history.back()" style="display: inline-block; padding: 10px 20px; text-align: center; text-decoration: none; background-color: #00ff00; border-radius: 5px; margin-right: 10px;">Back</a>
    </div>
');

// Variables
$booking_Date = "";
$booking_Time_Start = "";
$booking_Time_End = "";
$roomID = "";
$booking_Status = "";

// This block is called when the Submit button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "SELECT * FROM user WHERE userid=" . $_SESSION["UID"];
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userrole = $row["userrole"];
        if ($userrole == 1) {
            $booking_ID = $_POST["booking_ID"];
            $booking_Date = $_POST["booking_Date"];
            $booking_Time_Start = $_POST["booking_Time_Start"];
            $booking_Time_End = $_POST["booking_Time_End"];
            $roomID = $_POST["roomID"];
            $booking_Status = $_POST["booking_Status"];

            $sql = "UPDATE booking SET booking_Date = '$booking_Date', booking_Time_Start = '$booking_Time_Start', 
                    booking_Time_End = '$booking_Time_End', booking_Duration = TIME_FORMAT(TIMEDIFF('$booking_Time_End', '$booking_Time_Start'), '%H hours %i minutes'), 
                    room_ID = '$roomID', booking_Status='$booking_Status' WHERE booking_ID = $booking_ID";
            $status = update_DBTable($conn, $sql);
            if ($status) {
                echo "<h3 align=center>Form data saved successfully!</h3><br>" . SUCCESS_HTML;
            } else {
                echo "<h3 align=center>Failed to update</h3><br>" . FAILED_HTML;
            }
        } else {
            $booking_ID = $_POST["booking_ID"];
            $booking_Date = $_POST["booking_Date"];
            $booking_Time_Start = $_POST["booking_Time_Start"];
            $booking_Time_End = $_POST["booking_Time_End"];
            $roomID = $_POST["roomID"];

            $sql = "UPDATE booking SET booking_Date = '$booking_Date', booking_Time_Start = '$booking_Time_Start', 
                    booking_Time_End = '$booking_Time_End', booking_Duration = TIME_FORMAT(TIMEDIFF('$booking_Time_End', '$booking_Time_Start'), '%H hours %i minutes'), 
                    room_ID = '$roomID', booking_Status='Pending' WHERE booking_ID = $booking_ID";
            $status = update_DBTable($conn, $sql);
            if ($status) {
                echo "<h3 align=center>Form data saved successfully!</h3><br>" . SUCCESS_HTML;
            } else {
                echo "<h3 align=center>Failed to update</h3><br>" . FAILED_HTML;
            }
        }
    }
}

// Close db connection
mysqli_close($conn);

// Function to insert data to database table
function update_DBTable($conn, $sql)
{
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
        return false;
    }
}
?>
