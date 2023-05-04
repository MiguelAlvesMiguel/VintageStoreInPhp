<?php
    require 'dbConnection.php';

    function getUsers($conn) {
        $sql = "SELECT * FROM Users";
        $result = mysqli_query($conn, $sql);

        $users = [];

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
        }

        return $users;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Information</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
</head>
<body>

<!-- Navigation-->
 <?php include 'navbar.php'; ?>

<div class="container">
    <h1>User Information</h1>
    <div class="row">
        <div class="col">
            <form>
                <div class="form-group">
                    <label for="search">Search:</label>
                    <input type="text" class="form-control" id="search" placeholder="Search...">
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select class="form-control" id="gender">
                        <option value="">All</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="age">Age Range:</label>
                    <select class="form-control" id="age">
                        <option value="">All</option>
                        <option value="18-25">18-25</option>
                        <option value="26-35">26-35</option>
                        <option value="36-45">36-45</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
    
    <br>

    <br>


    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Location</th>
            <th>Zip Code</th>
            <th>Gender</th>
            <th>Age Range</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $users = getUsers($conn);
            foreach ($users as $user) {
                $birthDate = new DateTime($user['date_of_birth']);
                $now = new DateTime();
                $age = $now->diff($birthDate)->y;

                if ($age >= 18 && $age <= 25) {
                    $ageRange = "18-25";
                } elseif ($age >= 26 && $age <= 35) {
                    $ageRange = "26-35";
                } elseif ($age >= 36 && $age <= 45) {
                    $ageRange = "36-45";
                } else {
                    $ageRange = "Other";
                }
        ?>
            <tr>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['city']; ?></td>
                <td><?php echo $user['postal_code']; ?></td>
                <td><?php echo $user['gender']; ?></td>
                <td><?php echo $ageRange; ?></td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>

    <script>
       $(document).ready(function () {
    // Activate table search
    $('#search').on('keyup', function () {
        var value = $(this).val().toLowerCase();
        $('tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Activate gender filter
    $('#gender').on('change', function () {
        var value = $(this).val().toLowerCase();
        $('tbody tr').filter(function () {
            if (value === '') {
                return true;
            } else {
                return $(this).find('td:nth-child(5)').text().toLowerCase() === value;
            }
        }).toggle();
    });

    // Activate age range filter
    $('#age').on('change', function () {
        var value = $(this).val().toLowerCase();
        $('tbody tr').filter(function () {
            if (value === '') {
                return true;
            } else {
                return $(this).find('td:nth-child(6)').text().toLowerCase() === value;
            }
        }).toggle();
    });
});

    </script>
    </body>
    </html>