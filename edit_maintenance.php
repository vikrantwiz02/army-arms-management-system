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
  echo "No maintenance record ID provided.";
  exit;
}

// Fetch the maintenance record
$sql = "SELECT * FROM Maintenance_Records WHERE Maintenance_ID = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
  echo "No maintenance record found with ID $id.";
  exit;
}

$maintenance = $result->fetch_assoc();

// Update maintenance record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_maintenance'])) {
  $weapon_id = sanitize_input($_POST['weapon_id']);
  $type = sanitize_input($_POST['type']);
  $technician_name = sanitize_input($_POST['technician_name']);
  $maintenance_notes = sanitize_input($_POST['maintenance_notes']);
  $maintenance_date = sanitize_input($_POST['maintenance_date']);
  $next_maint_date = sanitize_input($_POST['next_maint_date']);

  $sql = "UPDATE Maintenance_Records SET 
          Weapon_ID = '$weapon_id', 
          Type = '$type', 
          Technician_Name = '$technician_name', 
          Maintenance_Notes = '$maintenance_notes', 
          Maintenance_Date = '$maintenance_date', 
          Next_Maint_Date = '$next_maint_date'
          WHERE Maintenance_ID = $id";

  if ($conn->query($sql) === TRUE) {
      echo "Maintenance record updated successfully";
      // Refresh the maintenance data
      $result = $conn->query("SELECT * FROM Maintenance_Records WHERE Maintenance_ID = $id");
      $maintenance = $result->fetch_assoc();
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
  <title>Edit Maintenance Record</title>
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
      <center><h1>Edit Maintenance Record</h1></center>
  </header>
  <main>
      <center><h2>Edit Maintenance Record <?php echo $id; ?></h2></center>
      <form action="" method="POST">
          <label for="weapon_id">Weapon ID:</label>
          <input type="number" id="weapon_id" name="weapon_id" value="<?php echo $maintenance['Weapon_ID']; ?>" required>

          <label for="type">Maintenance Type:</label>
          <input type="text" id="type" name="type" value="<?php echo $maintenance['Type']; ?>" required>

          <label for="technician_name">Technician Name:</label>
          <input type="text" id="technician_name" name="technician_name" value="<?php echo $maintenance['Technician_Name']; ?>" required>

          <label for="maintenance_notes">Maintenance Notes:</label>
          <textarea id="maintenance_notes" name="maintenance_notes" required><?php echo $maintenance['Maintenance_Notes']; ?></textarea>

          <label for="maintenance_date">Maintenance Date:</label>
          <input type="date" id="maintenance_date" name="maintenance_date" value="<?php echo $maintenance['Maintenance_Date']; ?>" required>

          <label for="next_maint_date">Next Maintenance Date:</label>
          <input type="date" id="next_maint_date" name="next_maint_date" value="<?php echo $maintenance['Next_Maint_Date']; ?>" required>

          <input type="submit" name="update_maintenance" value="Update Maintenance Record">
      </form>
      </main>
  <script src="js/main.js"></script>
</body>
</html>
