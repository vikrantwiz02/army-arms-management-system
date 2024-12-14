<?php
include 'includes/db_connect.php';

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Add new soldier
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_soldier'])) {
    $soldier_id = sanitize_input($_POST['soldier_id']);
    $name = sanitize_input($_POST['name']);
    $rank = sanitize_input($_POST['rank']);
    $unit = sanitize_input($_POST['unit']);
    $weapon_id = sanitize_input($_POST['weapon_id']);
    $joining_date = sanitize_input($_POST['joining_date']);
    $contact = sanitize_input($_POST['contact']);

    $sql = "INSERT INTO Soldiers (Soldier_ID ,Name, `Rank`, Unit, Weapon_ID, Joining_Date, Contact) 
            VALUES ('$soldier_id','$name', '$rank', '$unit', '$weapon_id', '$joining_date', '$contact')";

    if ($conn->query($sql) === TRUE) {
        echo "New soldier added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete soldier
if (isset($_GET['delete'])) {
    $id = sanitize_input($_GET['delete']);
    $sql = "DELETE FROM Soldiers WHERE Soldier_ID = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Soldier deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch soldiers
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$sql = "SELECT * FROM Soldiers WHERE Name LIKE '%$search%' OR `Rank` LIKE '%$search%' OR Unit LIKE '%$search%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soldiers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('./military-camouflage-army-cloth-texture-background-free-vector.jpg');
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
        .weapon_header {
            font-family: 'Times New Roman', Times, serif;
            font-size: 24px;
            margin-top: -40px;
        }
        .searchbar_add_row_container {
            margin-top: -1%;
            display: flex;
            flex-direction: column-reverse;
        }
        .searchbar input[type="search"] {
            width: 30%;
            height: 40px;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 5px;
            float: right;
            margin-right: 15px;
        }
        .icon-button {
            background-color: #f9f9f9;
            color: rgb(83, 70, 70);
            padding: 10px 20px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            float: right;
            margin-right: 72px;
        }
        .add-button {
            background-color: #ff9900;
            border-radius: 5px;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            float: right;
            margin-right: 10px;
        }
        .add-form-container{
            margin-top: 5px;
        }
        .submit-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        .table-container {
            width: 94%;
            margin: 20px auto;
            padding: 1%;
            background-color: #f9f9f9;
            border: 1.5px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            position: relative;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropbtn {
            background-color: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            margin-left: 5px;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown.active .dropdown-content {
            display: block;
        }
        .edit-button, .delete-button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            padding: 5px;
            margin: 0 5px;
        }
        .message {
            background-color: white;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <main>
        <div class="weapon_container">
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <div class="weapon_header">
            <center><h1>Soldiers</h1></center>
            <div class="searchbar_add_row_container">
                <div class="searchbar">
                    <button class="add-button" onclick="showAddForm()">ADD</button>
                    <button class="icon-button" id="search-button" onclick="searchWeapons()"><i class="fas fa-search"></i></button>
                    <input type="search" id="search-input" placeholder="Search...">
                </div>
            </div>
        </div>
        <div class="add-form-container" style="display: none;">
        <div class="add-form">
            <h3>Add New Soldier</h3>
            <form action="" method="POST">
                <input type="number" name="soldier_id" placeholder="Soldier ID" required>
                <input type="text" name="name" placeholder="Soldier Name" required>
                <input type="text" name="rank" placeholder="Rank" required>
                <input type="text" name="unit" placeholder="Unit" required>
                <input type="number" name="weapon_id" placeholder="Weapon ID" required>
                <input type="date" name="joining_date" required>
                <input type="text" name="contact" placeholder="Contact" required>
                <input type="submit" name="add_soldier" value="Add Soldier">
            </form>
        </div>
        </div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                    <th>Soldier ID</th>
                    <th>Name</th>
                    <th>Rank</th>
                    <th>Unit</th>
                    <th>Weapon ID</th>
                    <th>Joining Date</th>
                    <th>Contact</th>
                    <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["Soldier_ID"] . "</td>";
                            echo "<td>" . $row["Name"] . "</td>";
                            echo "<td>" . $row["Rank"] . "</td>";
                            echo "<td>" . $row["Unit"] . "</td>";
                            echo "<td>" . $row["Weapon_ID"] . "</td>";
                            echo "<td>" . $row["Joining_Date"] . "</td>";
                            echo "<td>" . $row["Contact"] . "</td>";
                            echo "<td>
                                    <a href='edit_soldier.php?id=" . $row["Soldier_ID"] . "'>Edit</a> | 
                                    <a href='?delete=" . $row["Soldier_ID"] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No Soldiers found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
        function showAddForm() {
            var form = document.querySelector('.add-form-container');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }

        function searchWeapons() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("search-input");
            filter = input.value.toUpperCase();
            table = document.getElementById("table-body");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }

        function toggleDropdown(button) {
            var dropdown = button.closest('.dropdown');
            dropdown.classList.toggle('active');
        }

        function filterType(type) {
            var table, tr, td, i;
            table = document.getElementById("table-body");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[2];
                if (td) {
                    if (type === '' || td.textContent === type) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
            closeAllDropdowns();
        }

        function filterStatus(status) {
            var table, tr, td, i;
            table = document.getElementById("table-body");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[6];
                if (td) {
                    if (status === '' || td.textContent === status) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
            closeAllDropdowns();
        }

        function closeAllDropdowns() {
            var dropdowns = document.getElementsByClassName("dropdown");
            for (var i = 0; i < dropdowns.length; i++) {
                dropdowns[i].classList.remove('active');
            }
        }
    </script>
</body>
</html>