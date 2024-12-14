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
  echo "No inspection ID provided.";
  exit;
}

// Fetch the inspection record
$sql = "SELECT * FROM Weapon_Inspections WHERE Insp_ID = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
  echo "No inspection found with ID $id.";
  exit;
}

$inspection = $result->fetch_assoc();

// Update inspection record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_inspection'])) {
  $weapon_id = sanitize_input($_POST['weapon_id']);
  $insp_date = sanitize_input($_POST['insp_date']);
  $inspector_name = sanitize_input($_POST['inspector_name']);
  $findings = sanitize_input($_POST['findings']);
  $action_taken = sanitize_input($_POST['action_taken']);
  $next_insp_date = sanitize_input($_POST['next_insp_date']);

  $sql = "UPDATE Weapon_Inspections SET 
          Weapon_ID = '$weapon_id', 
          Insp_Date = '$insp_date', 
          Inspector_Name = '$inspector_name', 
          Findings = '$findings', 
          Action_Taken = '$action_taken', 
          Next_Insp_Date = '$next_insp_date'
          WHERE Insp_ID = $id";

  if ($conn->query($sql) === TRUE) {
      echo "Inspection record updated successfully";
      // Refresh the inspection data
      $result = $conn->query("SELECT * FROM Weapon_Inspections WHERE Insp_ID = $id");
      $inspection = $result->fetch_assoc();
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
  <title>Edit Inspection Record</title>
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
        .weapon_container {
            width: 94%;
            margin: 20px auto;
            padding: 1%;
            background-color: #f9f9f9;
            border: 1.5px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            height: auto;
            min-height: 10%;
            max-height: 40vh;
        }  
  </style>
</head>
<body>
  <header>
      <center><h1>Edit Inspection Record</h1></center>
  </header>
  <main>

  <center><h2>Edit Inspection Record <?php echo $id; ?></h2></center>
      <form action="" method="POST">
          <label for="weapon_id">Weapon ID:</label>
          <input type="number" id="weapon_id" name="weapon_id" value="<?php echo $inspection['Weapon_ID']; ?>" required>

          <label for="insp_date">Inspection Date:</label>
          <input type="date" id="insp_date" name="insp_date" value="<?php echo $inspection['Insp_Date']; ?>" required>

          <label for="inspector_name">Inspector Name:</label>
          <input type="text" id="inspector_name" name="inspector_name" value="<?php echo $inspection['Inspector_Name']; ?>" required>

          <label for="findings">Findings:</label>
          <textarea id="findings" name="findings" required><?php echo $inspection['Findings']; ?></textarea>

          <label for="action_taken">Action Taken:</label>
          <textarea id="action_taken" name="action_taken" required><?php echo $inspection['Action_Taken']; ?></textarea>

          <label for="next_insp_date">Next Inspection Date:</label>
          <input type="date" id="next_insp_date" name="next_insp_date" value="<?php echo $inspection['Next_Insp_Date']; ?>" required>

          <input type="submit" name="update_inspection" value="Update Inspection Record">
      </form>
  </main>
  <script src="js/main.js"></script>
</body>
</html>

