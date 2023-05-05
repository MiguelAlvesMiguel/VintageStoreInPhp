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
    function getTransactions($conn) {
        $sql = "SELECT Transactions.transaction_id, Transactions.buyer_id, Transactions.product_id, Transactions.purchase_date, Transactions.price,
                buyer.name AS buyer_name, buyer.email AS buyer_email,
                seller.name AS seller_name, seller.email AS seller_email,
                Products.title
                FROM Transactions
                INNER JOIN Users AS buyer ON Transactions.buyer_id = buyer.user_id
                INNER JOIN Products ON Transactions.product_id = Products.product_id
                INNER JOIN Users AS seller ON Products.seller_id = seller.user_id";
        $result = mysqli_query($conn, $sql);
    
        $transactions = [];
    
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $transactions[] = $row;
            }
        }
    
        return $transactions;
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
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>
               
            </form>
        </div>
    </div>
    
    <br>
    <!-- Add this inside the 'container' div, after the 'row' div and before the 'table' -->
<!-- Transaction Summary -->
<div class="container mt-5">
    <h2>Transaction Summary</h2>
    <?php
        $transactions = getTransactions($conn);
        $totalTransactions = count($transactions);
        $totalRevenue = 0;

        foreach ($transactions as $transaction) {
            $totalRevenue += $transaction['price'];
        }
    ?>
    <p>Total Transactions: <?php echo $totalTransactions; ?></p>
    <p>Total Revenue: $<?php echo number_format($totalRevenue, 2); ?></p>
</div>

<!-- Transaction List -->
<div class="container mt-5">
    <h2>Transaction List</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Buyer Name</th>
                <th>Buyer Email</th>
                <th>Seller Name</th>
                <th>Seller Email</th>
                <th>Product Title</th>
                <th>Purchase Date</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($transactions as $transaction) {
            ?>
                <tr>
                    <td><?php echo $transaction['transaction_id']; ?></td>
                    <td><?php echo $transaction['buyer_name']; ?></td>
                    <td><?php echo $transaction['buyer_email']; ?></td>
                    <td><?php echo $transaction['seller_name']; ?></td>
                    <td><?php echo $transaction['seller_email']; ?></td>
                    <td><?php echo $transaction['title']; ?></td>
                    <td><?php echo $transaction['purchase_date']; ?></td>
                    <td>$<?php echo number_format($transaction['price'], 2); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


<!-- Add a horizontal line to separate the summaries from the table -->
<hr>

    <br>

    
    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Location</th>
            <th>Zip Code</th>
            <th>Gender</th>
            <th>Age</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $users = getUsers($conn);
            foreach ($users as $user) {
                $birthDate = new DateTime($user['date_of_birth']);
                $now = new DateTime();
                $age = $now->diff($birthDate)->y;

               
        ?>
            <tr>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['city']; ?></td>
                <td><?php echo $user['postal_code']; ?></td>
                <td><?php echo $user['gender']; ?></td>
                <td><?php echo $age; ?></td>
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


  
});

    </script>


    

    </body>
    </html>