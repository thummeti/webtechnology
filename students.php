<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Students</title>
    <style>
        /* Your CSS styles here */
        table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
            overflow-x: auto;
        }

        th, td {
            padding: 10px;
            border: 1px solid #000;
        }

        th {
            background-color: #0000;
        }
        
        body{
            
             background-image: url('user_background.jpg'); 
            background-size: cover; 
            background-position: center; 
            color:white;
        }
    </style>
</head>

<body>
    <h1 style="margin-left:40%;">Registered Students</h1>
    <div class="scrollable-table">
        <table>
            <thead>
                <tr>
                    <th>UMID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Project Title</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Time Slot</th>
                </tr>
            </thead>
            <tbody>
               <?php

$servername = "thummeti-yeswanth-cis525.cqepjarvasud.us-east-2.rds.amazonaws.com"; 
$username = "thummeti";
$password = "thummeti123";
$database = "yeswanth_stest";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$getStudentsQuery = "SELECT s.umid, s.first_name, s.last_name, s.project_title, s.email, s.phone_number, t.start_time, t.end_time
FROM students s
INNER JOIN time_slots t ON s.time_slot_id = t.id;
";

$result = $conn->query($getStudentsQuery);

//print_r($result); die;
if (!$result) {
    die("Query failed: " . $conn->error);
}


                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['umid'] . "</td>";
                        echo "<td>" . $row['first_name'] . "</td>";
                        echo "<td>" . $row['last_name'] . "</td>";
                        echo "<td>" . $row['project_title'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['phone_number'] . "</td>";
                        echo "<td>" . $row['start_time'] . " - " . $row['end_time'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No students registered yet.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
