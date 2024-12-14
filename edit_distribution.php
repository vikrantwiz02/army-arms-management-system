<?php
include 'includes/db_connect.php';

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$id = isset($_GET['id']) ? sanitize_input($_GET['id']) : null;

if ($id === null) {
    echo "No distribution record ID provided.";
    exit;
}

// Fetch the distribution record
$sql = "SELECT * FROM Weapons_Distribution WHERE Dist_ID = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "No distribution record found with ID $id.";
    exit;
}

$distribution = $result->fetch_assoc();

// Update distribution record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_distribution'])) {
    $soldier_id = sanitize_input($_POST['soldier_id']);
    $weapon_id = sanitize_input($_POST['weapon_id']);
    $distribution_date = sanitize_input($_POST['distribution_datedistribution_date']);
    $return_date = sanitize_input($_POST['return_date']);
    $dist_cond = sanitize_input($_POST['dist_cond']);
    $return_cond = sanitize_input($_POST['return_cond']);
    $dist_notes = sanitize_input($_POST['dist_notes']);

    $sql = "UPDATE Weapons_Distribution SET 
            Soldier_ID = '$soldier_id', 
            Weapon_ID = '$weapon_id', 
            Distribution = '$distribution_date', 
            `Return` = '$return_date', 
            Dist_Cond = '$dist_cond', 
            Return_Cond = '$return_cond', 
            Dist_Notes = '$dist_notes'
            WHERE Dist_ID = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Distribution record updated successfully";
        // Refresh the distribution data
        $result = $conn->query("SELECT * FROM Weapons_Distribution WHERE Dist_ID = $id");
        $distribution = $result->fetch_assoc();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Distribution Record</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
    body {
            font-family: 'Roboto', sans-serif;
            background-size: cover;
            height: 100vh;
            color: #0a0000;
            background-attachment: fixed;
            margin: 0;
        }
  </style>
</head>
<body>
    <header>
        <center><h1>Edit Distribution Record</h1></center>
    <main>
        <center><h2>Edit Distribution Record #<?php echo $id; ?></h2></center>
        <form action="" method="POST">
            <label for="soldier_id">Soldier ID:</label>
            <input type="number" id="soldier_id" name="soldier_id" value="<?php echo $distribution['Soldier_ID']; ?>" required>

            <label for="weapon_id">Weapon ID:</label>
            <input type="number" id="weapon_id" name="weapon_id" value="<?php echo $distribution['Weapon_ID']; ?>" required>

            <label for="distribution_date">Distribution Date:</label>
            <input type="date" id="distribution_date" name="distribution_date" value="<?php echo $distribution['Distribution']; ?>" required>

            <label for="return_date">Return Date:</label>
            <input type="date" id="return_date" name="return_date" value="<?php echo $distribution['Return']; ?>">

            <label for="dist_cond">Distribution Condition:</label>
            <input type="text" id="dist_cond" name="dist_cond" value="<?php echo $distribution['Dist_Cond']; ?>" required>

            <label for="return_cond">Return Condition:</label>
            <input type="text" id="return_cond" name="return_cond" value="<?php echo $distribution['Return_Cond']; ?>">

            <label for="dist_notes">Distribution Notes:</label>
            <textarea id="dist_notes" name="dist_notes"><?php echo $distribution['Dist_Notes']; ?></textarea>

            <input type="submit" name="update_distribution" value="Update Distribution Record">
        </form>
    </main>
    <script src="js/main.js"></script>
</body>
</html>
