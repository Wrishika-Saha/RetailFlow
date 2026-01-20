<?php
include("../Model/salesreport.php");
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sales Report - Seller Dashboard</title>
  <link rel="stylesheet" href="salesreport.css">
</head>
<body>

  <a href="logout.php" class="logout-btn">Logout</a>
  <a href="../View/sellerdashboard.php" class="back-btn">Back</a>
  
  <div class="card">
    <h2>Sales Report</h2>
    <div class="stat">Total Sales: <strong>৳ <?= number_format($total_sales, 2) ?></strong></div>
    <div class="stat">Total Orders: <strong><?= htmlspecialchars($total_orders) ?></strong></div>
    <div class="stat">Best Selling Product: 
      <strong>
        <?= $top_product ? htmlspecialchars($top_product['product_name'])." (৳ ".number_format($top_product['total_sales'], 2).")" : "No sales yet" ?>
      </strong>
    </div>
  </div>

  <div class="card">
    <h2>Payments</h2>
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Payment Method</th>
          <th>Status</th>
          <th>Amount</th>
          <th>Created At</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($payments): ?>
          <?php foreach ($payments as $payment): ?>
            <tr>
              <td><?= htmlspecialchars($payment['order_id']) ?></td>
              <td><?= htmlspecialchars($payment['payment_method']) ?></td>
              <td><?= htmlspecialchars($payment['status']) ?></td>
              <td>৳ <?= number_format($payment['amount'], 2) ?></td>
              <td><?= htmlspecialchars($payment['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5">No payments available</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</body>
</html>
