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
  echo "No soldier ID provided.";
  exit;
}

// Fetch the soldier record
$sql = "SELECT * FROM Soldiers WHERE Soldier_ID = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
  echo "No soldier found with ID $id.";
  exit;
}

$soldier = $result->fetch_assoc();

// Update soldier record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_soldier'])) {
  $name = sanitize_input($_POST['name']);
  $rank = sanitize_input($_POST['rank']);
  $unit = sanitize_input($_POST['unit']);
  $weapon_id = sanitize_input($_POST['weapon_id']);
  $joining_date = sanitize_input($_POST['joining_date']);
  $contact = sanitize_input($_POST['contact']);

  $sql = "UPDATE Soldiers SET 
          Name = '$name', 
          `Rank` = '$rank', 
          Unit = '$unit', 
          Weapon_ID = '$weapon_id', 
          Joining_Date = '$joining_date', 
          Contact = '$contact'
          WHERE Soldier_ID = $id";

  if ($conn->query($sql) === TRUE) {
      echo "Soldier record updated successfully";
      // Refresh the soldier data
      $result = $conn->query("SELECT * FROM Soldiers WHERE Soldier_ID = $id");
      $soldier = $result->fetch_assoc();
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
  <title>Edit Soldier Record</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
      <center><h1>Edit Soldier Record</h1></center>
  </header>
  <main>
      <center><h2>Edit Soldier Record <?php echo $id; ?></h2></center>
      <form action="" method="POST">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" value="<?php echo $soldier['Name']; ?>" required>

          <label for="rank">Rank:</label>
          <input type="text" id="rank" name="rank" value="<?php echo $soldier['Rank']; ?>" required>

          <label for="unit">Unit:</label>
          <input type="text" id="unit" name="unit" value="<?php echo $soldier['Unit']; ?>" required>

          <label for="weapon_id">Weapon ID:</label>
          <input type="number" id="weapon_id" name="weapon_id" value="<?php echo $soldier['Weapon_ID']; ?>" required>

          <label for="joining_date">Joining Date:</label>
          <input type="date" id="joining_date" name="joining_date" value="<?php echo $soldier['Joining_Date']; ?>" required>

          <label for="contact">Contact:</label>
          <input type="text" id="contact" name="contact" value="<?php echo $soldier['Contact']; ?>" required>

          <input type="submit" name="update_soldier" value="Update Soldier Record">
      </form>
  </main>
  <script src="js/main.js"></script>
</body>
</html>
