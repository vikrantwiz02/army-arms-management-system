<?php
include 'includes/db_connect.php';

// Function to sanitize input
function sanitize_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';

function searchTable($conn, $table, $columns, $search) {
  $conditions = [];
  foreach ($columns as $column) {
      $conditions[] = "$column LIKE '%$search%'";
  }
  $sql = "SELECT * FROM $table WHERE " . implode(' OR ', $conditions);
  return $conn->query($sql);
}

$weapons_result = searchTable($conn, 'Weapons', ['Weapon_ID', 'Name', 'Type', 'Caliber', 'Manufacturer', 'Acquisition_Date', 'Status'], $search);
$soldiers_result = searchTable($conn, 'Soldiers', ['Soldier_ID', 'Name', '`Rank`', 'Unit', 'Weapon_ID', 'Joining_Date', 'Contact'], $search);
$maintenance_result = searchTable($conn, 'Maintenance_Records', ['Maintenance_ID', 'Weapon_ID', 'Type', 'Technician_Name', 'Maintenance_Notes', 'Maintenance_Date', 'Next_Maint_Date'], $search);
$distribution_result = searchTable($conn, 'Weapons_Distribution', ['Dist_ID', 'Soldier_ID', 'Weapon_ID', 'Distribution', '`Return`', 'Dist_Cond', 'Return_Cond', 'Dist_Notes'], $search);
$inspection_result = searchTable($conn, 'Inspections', ['Insp_ID', 'Weapon_ID', 'Insp_Date', 'Inspector_Name', 'Findings', 'Action_Taken', 'Next_Insp_Date'], $search);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Global Search - Army Arms Management System</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
      <h1>Global Search</h1>
      <nav>
          <ul>
              <li><a href="index.html">Home</a></li>
              <li><a href="weapons.php">Weapons</a></li>
              <li><a href="soldiers.php">Soldiers</a></li>
              <li><a href="maintenance.php">Maintenance Records</a></li>
              <li><a href="distribution.php">Weapon Distribution</a></li>
              <li><a href="inspection.php">Inspection</a></li>
              <li><a href="global_search.php">Global Search</a></li>
          </ul>
      </nav>
  </header>
  <main>
      <h2>Global Search Results</h2>
      <div class="search-bar">
          <form action="" method="GET">
              <input type="text" name="search" placeholder="Search across all modules..." value="<?php echo $search; ?>">
              <input type="submit" value="Search">
          </form>
      </div>
      
      <?php
      function displayResults($result, $title) {
          if ($result->num_rows > 0) {
              echo "<h3>$title</h3>";
              echo "<table>";
              $first = true;
              while ($row = $result->fetch_assoc()) {
                  if ($first) {
                      echo "<tr>";
                      foreach ($row as $key => $value) {
                          echo "<th>" . str_replace('_', ' ', $key) . "</th>";
                      }
                      echo "</tr>";
                      $first = false;
                  }
                  echo "<tr>";
                  foreach ($row as $value) {
                      echo "<td>$value</td>";
                  }
                  echo "</tr>";
              }
              echo "</table>";
          }
      }

      displayResults($weapons_result, "Weapons");
      displayResults($soldiers_result, "Soldiers");
      displayResults($maintenance_result, "Maintenance Records");
      displayResults($distribution_result, "Weapon Distribution");
      displayResults($inspection_result, "Inspections");
      ?>
  </main>
  <footer>
      <p>&copy; 2023 Army Arms Management System</p>
  </footer>
  <script src="js/main.js"></script>
</body>
</html>

