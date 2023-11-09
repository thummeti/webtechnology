<?php
session_start();

$servername = "thummeti-yeswanth-cis525.cqepjarvasud.us-east-2.rds.amazonaws.com"; 
$username = "thummeti";
$password = "thummeti123";
$database = "yeswanth_stest";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user ID is set in the session
if (isset($_SESSION['user_id'])) {
    // Retrieve the user's ID from the session
    $userId = $_SESSION['user_id'];

    // Retrieve user data from students table
    $checkRegistrationQuery = "SELECT * FROM students WHERE umid = $userId";
    $result = $conn->query($checkRegistrationQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $umid = $row['umid'];
        $firstName = $row['first_name'];
        $lastName = $row['last_name'];
        $projectTitle = $row['project_title'];
        $email = $row['email'];
        $phone = $row['phone_number'];
        $selectedTimeSlotId = $row['time_slot_id'];
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Update Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('user_background.jpeg'); /* Replace 'path/to/your/image.jpg' with the actual path to your background image file */
            background-size: cover; /* This ensures the background image covers the whole body */
            background-repeat: no-repeat; /* Prevents the background image from repeating */
            background-position: center; /* Centers the background image */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

       .container {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 50%;
    margin-top: 10%;
    box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .container {
        width: 100%; /* Set width to 100% on screens smaller than or equal to 768px (typical mobile devices) */
    }
}

        .form-group {
            margin-bottom: 20px;
            padding-right:20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="text"] {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
        }
    </style>

</head>
<body>
    <div class="container">
        <h1 style="margin-left:30%;">Update Registration Form</h1>
        <form action="update_form_submit.php" method="post" onsubmit="return confirmUpdate();">
            <div class="form-group">
                <label for="umid">UMID:</label>
<input type="text" id="umid" name="umid" required pattern="[0-9]{8}" placeholder="8 Digit UMID Number" maxlength="8" value="<?php echo $umid; ?>">

            </div>
            <div class="form-group">
                <label for="firstName">First Name:</label>
<input type="text" id="firstName" name="firstName" placeholder="First Name" required value="<?php echo $firstName; ?>">
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
<input type="text" id="lastName" name="lastName" placeholder="Last Name" required value="<?php echo $lastName; ?>">
            </div>
            <div class="form-group">
                <label for="projectTitle">Project Title:</label>
<input type="text" id="projectTitle" name="projectTitle" required value="<?php echo $projectTitle; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email Address:</label>
<input type="text" id="email" name="email" required placeholder="example@example.com" value="<?php echo $email; ?>">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
<input type="text" id="phone" name="phone" placeholder="999-999-9999" required value="<?php echo $phone; ?>">
            </div>
            <div class="form-group">
                <label for="timeSlot">Select Time Slot:</label>
 
   <select id="timeSlot" name="timeSlot" required>
                    <?php
                    $availableTimeSlots = array(
                        "6:00 PM – 7:00 PM",
                        "7:00 PM – 8:00 PM",
                        "8:00 PM – 9:00 PM",
                        "9:00 PM – 10:00 PM",
                        "10:00 PM – 11:00 PM",
                        "11:00 PM – 12:00 AM"
                    );

                    $servername = "localhost";
                    $username = "capital4_stest";
                    $password = "crn)PrHvH%qU";
                    $database = "capital4_stest";

                    $conn = new mysqli($servername, $username, $password, $database);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $getAvailableSlotsQuery = "SELECT start_time, end_time, allocated_seats, total_seats FROM time_slots WHERE date = CURDATE()";
                    $result = $conn->query($getAvailableSlotsQuery);

                    $availableSlots = array(6, 6, 6, 6, 6, 6); // Initialize all slots as available

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $startTime = $row['start_time'];
                            $endTime = $row['end_time'];
                            $index = array_search("$startTime – $endTime", $availableTimeSlots);
                            if ($index !== false) {
                                $availableSeats = $row['total_seats'] - $row['allocated_seats'];
                                $availableSlots[$index] = $availableSeats;
                            }
                        }
                    }

                    for ($i = 0; $i < count($availableTimeSlots); $i++) {
                        echo '<option value="' . ($i + 1) . '">' . $availableTimeSlots[$i] . ' (Available Seats: ' . $availableSlots[$i] . ')</option>';
                    }

                    $conn->close();
                    ?>
                </select>




            </div>
            <div class="form-group">
                <input type="submit" value="Update">
            </div>
        </form>
        
        <div class="form-group">
                <p style="margin-left:40%;"><a href="students.php">Check All Students</a></p>
            </div>
    </div>
</body>
    <script>
    document.getElementById('umid').addEventListener('input', function(event) {
        var input = event.target.value;
        var numericInput = input.replace(/\D/g, ''); // Remove non-numeric characters
        
        if (input !== numericInput) {
            event.target.value = numericInput; // Update the input field with only numeric characters
        }
    });
    
     document.getElementById('firstName').addEventListener('input', function(event) {
        var input = event.target.value;
        var alphabeticInput = input.replace(/[^a-zA-Z]/g, ''); // Remove non-alphabetic characters
        
        event.target.value = alphabeticInput; // Update the input field with only alphabetic characters
    });
    
     document.getElementById('lastName').addEventListener('input', function(event) {
        var input = event.target.value;
        var alphabeticInput = input.replace(/[^a-zA-Z]/g, ''); // Remove non-alphabetic characters
        
        event.target.value = alphabeticInput; // Update the input field with only alphabetic characters
    });
    document.getElementById('phone').addEventListener('input', function(event) {
        var input = event.target.value;
        var formattedInput = input.replace(/\D/g, ''); // Remove non-numeric characters
        var regex = /^(\d{3})(\d{3})(\d{4})$/;

        if (formattedInput.length > 2 && formattedInput.length <= 10) {
            formattedInput = formattedInput.replace(regex, '$1-$2-$3');
        } else if (formattedInput.length > 10) {
            formattedInput = formattedInput.substring(0, 10);
            formattedInput = formattedInput.replace(regex, '$1-$2-$3');
        }

        event.target.value = formattedInput; // Update the input field with formatted phone number
    });
     document.getElementById('email').addEventListener('input', function(event) {
        var emailInput = event.target.value;
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regular expression for basic email validation
        
        if (!emailPattern.test(emailInput)) {
            event.target.setCustomValidity("Invalid email address"); // Set a custom validation message
        } else {
            event.target.setCustomValidity(""); // Clear the custom validation message
        }
    });
    
    function confirmUpdate() {
        return confirm("Are you sure you want to update your information?");
    }

    
</script>

</html>
