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
  echo "No weapon ID provided.";
  exit;
}

// Fetch the weapon record
$sql = "SELECT * FROM Weapons WHERE Weapon_ID = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
  echo "No weapon found with ID $id.";
  exit;
}

$weapon = $result->fetch_assoc();

// Update weapon record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_weapon'])) {
  $name = sanitize_input($_POST['name']);
  $type = sanitize_input($_POST['type']);
  $caliber = sanitize_input($_POST['caliber']);
  $manufacturer = sanitize_input($_POST['manufacturer']);
  $acquisition_date = sanitize_input($_POST['acquisition_date']);
  $status = sanitize_input($_POST['status']);

  $sql = "UPDATE Weapons SET 
          Name = '$name', 
          Type = '$type', 
          Caliber = '$caliber', 
          Manufacturer = '$manufacturer', 
          Acquisition_Date = '$acquisition_date', 
          Status = '$status'
          WHERE Weapon_ID = $id";

  if ($conn->query($sql) === TRUE) {
      echo "Weapon record updated successfully";
      // Refresh the weapon data
      $result = $conn->query("SELECT * FROM Weapons WHERE Weapon_ID = $id");
      $weapon = $result->fetch_assoc();
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
  <title>Edit Weapon Record</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
      <center><h1>Edit Weapon Record</h1></center>
  </header>
  <main>
      <center><h2>Edit Weapon Record #<?php echo $id; ?></h2></center>
      <form action="" method="POST">
          <label for="name">Weapon Name:</label>
          <input type="text" id="name" name="name" value="<?php echo $weapon['Name']; ?>" required>

          <label for="type">Type:</label>
          <input type="text" id="type" name="type" value="<?php echo $weapon['Type']; ?>" required>

          <label for="caliber">Caliber:</label>
          <input type="text" id="caliber" name="caliber" value="<?php echo $weapon['Caliber']; ?>" required>

          <label for="manufacturer">Manufacturer:</label>
          <input type="text" id="manufacturer" name="manufacturer" value="<?php echo $weapon['Manufacturer']; ?>" required>

          <label for="acquisition_date">Acquisition Date:</label>
          <input type="date" id="acquisition_date" name="acquisition_date" value="<?php echo $weapon['Acquisition_Date']; ?>" required>

          <label for="status">Status:</label>
          <input type="text" id="status" name="status" value="<?php echo $weapon['Status']; ?>" required>

          <input type="submit" name="update_weapon" value="Update Weapon Record">
      </form>
  </main>
  <script src="js/main.js"></script>
</body>
</html>
