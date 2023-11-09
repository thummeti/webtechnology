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
    
        // Start the session (if not already started)
session_start();

// Assuming $userId contains the user's ID retrieved from the database
$_SESSION['user_id'] = $umid;

    // Display a confirmation popup before redirecting
    echo "<script>
        var confirmed = confirm('UMID already exists. Do you want to update your information?');
        if (confirmed) {
            window.location.href = 'user_update.php';
        } else {
            window.location.href = 'https://capitalwholesalersltd.co.uk/test'; // Redirect to another page or perform any other action
        }
    </script>";
    exit();
}
 else {
    // Check if the time slot already exists
    $checkTimeSlotQuery = "SELECT id, allocated_seats FROM time_slots WHERE date = CURDATE() AND start_time = '$start_time' AND end_time = '$end_time'";
    $timeSlotResult = $conn->query($checkTimeSlotQuery);

    if ($timeSlotResult->num_rows > 0) {
        // Time slot already exists, use the existing ID
        $row = $timeSlotResult->fetch_assoc();
        $timeSlotId = $row['id'];
        $allocatedSeats = $row['allocated_seats'];

        if ($allocatedSeats >= 6) {
                        echo "<script>alert('No available seats in the selected time slot.'); window.location.href = 'https://capitalwholesalersltd.co.uk/test';</script>";

        } else {
            // Update allocated_seats for the selected time slot
            $updateSeatsQuery = "UPDATE time_slots SET allocated_seats = allocated_seats + 1 WHERE id = '$timeSlotId'";
            if ($conn->query($updateSeatsQuery) === TRUE) {
                // Insert student data into the students table
                $insertStudentQuery = "INSERT INTO students (umid, first_name, last_name, project_title, email, phone_number, time_slot_id) VALUES ('$umid', '$firstName', '$lastName', '$projectTitle', '$email', '$phone', '$timeSlotId')";
                if ($conn->query($insertStudentQuery) === TRUE) {
                                            echo "<script>alert('Registration successful!'); window.location.href = 'https://capitalwholesalersltd.co.uk/test';</script>";

                } else {
                     echo "<script>alert('Error inserting student data'); window.location.href = 'https://capitalwholesalersltd.co.uk/test';</script>";
                }
            } else {
                                     echo "<script>alert('Error updating allocated seats'); window.location.href = 'https://capitalwholesalersltd.co.uk/test';</script>";

            }
        }
    } else {
        // Time slot doesn't exist, insert a new one
        $insertTimeSlotQuery = "INSERT INTO time_slots (date, start_time, end_time, allocated_seats, total_seats) VALUES (CURDATE(), '$start_time', '$end_time', 1, 6)";
        if ($conn->query($insertTimeSlotQuery) === TRUE) {
            $timeSlotId = $conn->insert_id; // Get the ID of the newly inserted time slot
            
                                  $insertStudentQuery = "INSERT INTO students (umid, first_name, last_name, project_title, email, phone_number, time_slot_id) VALUES ('$umid', '$firstName', '$lastName', '$projectTitle', '$email', '$phone', '$timeSlotId')";
                if ($conn->query($insertStudentQuery) === TRUE) {
                                            echo "<script>alert('Registration successful!'); window.location.href = 'https://capitalwholesalersltd.co.uk/test';</script>";

                } else {
                     echo "<script>alert('Error inserting student data'); window.location.href = 'https://capitalwholesalersltd.co.uk/test';</script>";
                }

        } else {
            
                  echo "<script>alert('Error inserting time slot'); window.location.href = 'https://capitalwholesalersltd.co.uk/test';</script>";
        }
    }
}

// Close database connection
$conn->close();

} else {
    // Handle invalid form submission (optional)
    echo "Invalid form submission.";
}

?>
