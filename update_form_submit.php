<?php

$servername = "thummeti-yeswanth-cis525.cqepjarvasud.us-east-2.rds.amazonaws.com"; 
$username = "thummeti";
$password = "thummeti123";
$database = "yeswanth_stest";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $umid = mysqli_real_escape_string($conn, $_POST['umid']);
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $projectTitle = mysqli_real_escape_string($conn, $_POST['projectTitle']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $timeSlotId = mysqli_real_escape_string($conn, $_POST['timeSlot']);
    
    // Set start_time and end_time based on selected timeSlotId
    switch ($timeSlotId) {
        case 1:
            $start_time = "6:00 PM";
            $end_time = "7:00 PM";
            break;
        case 2:
            $start_time = "7:00 PM";
            $end_time = "8:00 PM";
            break;
        case 3:
            $start_time = "8:00 PM";
            $end_time = "9:00 PM";
            break;
        case 4:
            $start_time = "9:00 PM";
            $end_time = "10:00 PM";
            break;
        case 5:
            $start_time = "10:00 PM";
            $end_time = "11:00 PM";
            break;
        case 6:
            $start_time = "11:00 PM";
            $end_time = "12:00 AM";
            break;
        default:
            echo "Invalid time slot selected.";
            exit;
    }

    // Check if UMID already exists
    $checkUmidQuery = "SELECT * FROM students WHERE umid = '$umid'";
    $umidResult = $conn->query($checkUmidQuery);

    if ($umidResult->num_rows > 0) {
        // UMID already exists, update the existing record
        $updateStudentQuery = "UPDATE students SET 
                                first_name = '$firstName',
                                last_name = '$lastName',
                                project_title = '$projectTitle',
                                email = '$email',
                                phone_number = '$phone',
                                time_slot_id = '$timeSlotId'
                              WHERE umid = '$umid'";

        if ($conn->query($updateStudentQuery) === TRUE) {
            echo "<script>alert('Record updated successfully!'); window.location.href = 'https://capitalwholesalersltd.co.uk/test';</script>";
        } else {
            echo "<script>alert('Error updating student data'); window.location.href = 'https://capitalwholesalersltd.co.uk/test';</script>";
        }
    } else {
        // UMID does not exist, proceed with the insertion logic
        // ... (Your existing insertion logic here)
    }

    // Close database connection
    $conn->close();
} else {
    // Handle invalid form submission (optional)
    echo "Invalid form submission.";
}
?>
