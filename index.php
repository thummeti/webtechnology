<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('user_background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
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
        width: 100%;
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
            background-color: #4a5450;
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
        <h1 style="margin-left:30%;">Student Registration</h1>
        <form action="form_submit.php" method="post" >
            <div class="form-group">
                <label for="UMid">UMID:</label>
<input type="text" id="umid" name="umid" required pattern="[0-9]{8}" placeholder="Enter 8 Digit UMID Number" maxlength="8">

            </div>
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName"  placeholder="Enter First Name" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" placeholder="Enter Last Name" required>
            </div>
            <div class="form-group">
                <label for="projectTitle">Project Title:</label>
                <input type="text" id="projectTitle" name="projectTitle" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="text" id="email" name="email" required placeholder="uniqname@umich.edu">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" placeholder="999-999-9999" required>
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

                    $servername = "thummeti-yeswanth-cis525.cqepjarvasud.us-east-2.rds.amazonaws.com"; 
                    $username = "thummeti";
                    $password = "thummeti123";
                    $database = "yeswanth_stest";

                    $conn = new mysqli($servername, $username, $password, $database);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $getAvailableSlotsQuery = "SELECT start_time, end_time, allocated_seats, total_seats FROM time_slots WHERE date = CURDATE()";
                    $result = $conn->query($getAvailableSlotsQuery);

                    $availableSlots = array(6, 6, 6, 6, 6, 6);

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
                <input type="submit" value="Register">
            </div>
        </form>
        
        <div class="form-group">
                <p style="margin-left:50%;"><a href="students.php">Registered Students List</a></p>
            </div>
    </div>
</body>
    <script>
    document.getElementById('umid').addEventListener('input', function(event) {
        var input = event.target.value;
        var numericInput = input.replace(/\D/g, '');
        
        if (input !== numericInput) {
            event.target.value = numericInput; 
        }
    });
    
     document.getElementById('firstName').addEventListener('input', function(event) {
        var input = event.target.value;
        var alphabeticInput = input.replace(/[^a-zA-Z]/g, '');
        
        event.target.value = alphabeticInput;
    });
    
     document.getElementById('lastName').addEventListener('input', function(event) {
        var input = event.target.value;
        var alphabeticInput = input.replace(/[^a-zA-Z]/g, '');
        
        event.target.value = alphabeticInput;
    });
    document.getElementById('phone').addEventListener('input', function(event) {
        var input = event.target.value;
        var formattedInput = input.replace(/\D/g, '');
        var regex = /^(\d{3})(\d{3})(\d{4})$/;

        if (formattedInput.length > 2 && formattedInput.length <= 10) {
            formattedInput = formattedInput.replace(regex, '$1-$2-$3');
        } else if (formattedInput.length > 10) {
            formattedInput = formattedInput.substring(0, 10);
            formattedInput = formattedInput.replace(regex, '$1-$2-$3');
        }

        event.target.value = formattedInput;
    });
     document.getElementById('email').addEventListener('input', function(event) {
        var emailInput = event.target.value;
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!emailPattern.test(emailInput)) {
            event.target.setCustomValidity("Invalid email address");
        } else {
            event.target.setCustomValidity("");
        }
    });
    
</script>

</html>
