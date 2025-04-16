<?php
$host = "localhost";
$username = "root";
$password = "Ali@20";
$dbname = "order_management";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT 
            u.name AS user_name,
            u.phone,
            u.address,
            o.id AS order_id,
            o.order_date,
            p.name AS product_name,
            p.price,
            oi.quantity,
            (p.price * oi.quantity) AS total_amount
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        ORDER BY o.order_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
      padding: 30px;
      font-family: 'Segoe UI', sans-serif;
    }
    .table-wrapper {
      background: white;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
    }
    #search {
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

<div class="container table-wrapper">
  <h2>📦 All Orders List</h2>

  <!-- ✅ Export to Excel Button -->
  <a href="export_orders.php" class="btn btn-success mb-3">⬇️ Export to Excel</a>

  <!-- 🔍 Search Bar -->
  <input type="text" id="search" class="form-control" placeholder="Search by user name, phone, or product...">

  <div class="table-responsive mt-3">
    <table class="table table-bordered table-striped" id="ordersTable">
      <thead class="table-primary">
        <tr>
          <th>#</th>
          <th>User Name</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Order ID</th>
          <th>Date</th>
          <th>Product</th>
          <th>Price (₹)</th>
          <th>Qty</th>
          <th>Total (₹)</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 1;
        $grandTotal = 0;
        if ($result->num_rows > 0):
          while($row = $result->fetch_assoc()):
            $grandTotal += $row['total_amount'];
        ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
            <td><?= $row['order_id'] ?></td>
            <td><?= date('d-M-Y', strtotime($row['order_date'])) ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td>₹ <?= number_format($row['price'], 2) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td>₹ <?= number_format($row['total_amount'], 2) ?></td>
          </tr>
        <?php endwhile; ?>
        <!-- Grand Total Row -->
        <tr class="fw-bold table-success">
          <td colspan="9" class="text-end">Grand Total:</td>
          <td>₹ <?= number_format($grandTotal, 2) ?></td>
        </tr>
        <?php else: ?>
          <tr><td colspan="10">No orders found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- 🔍 Search Script -->
<script>
  const search = document.getElementById('search');
  const rows = document.querySelectorAll('#ordersTable tbody tr');

  search.addEventListener('keyup', function () {
    const query = this.value.toLowerCase();
    rows.forEach(row => {
      const cells = row.querySelectorAll('td');
      let match = false;
      cells.forEach(cell => {
        if (cell.textContent.toLowerCase().includes(query)) {
          match = true;
        }
      });
      row.style.display = match ? '' : 'none';
    });
  });
</script>

</body>
</html>

<?php $conn->close(); ?>
