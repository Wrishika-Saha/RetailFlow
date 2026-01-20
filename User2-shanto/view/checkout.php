<?php
$page_title = "Checkout";
include 'header.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php?page=login");
    exit;
}

require_once __DIR__ . '/../Model/Database.php';
require_once __DIR__ . '/../Model/Cart.php';

$db = new Database();
$cart = new Cart($db);
$cart_items = $cart->getCartItems($_SESSION['user']['id']);
$total = $cart->getCartTotal($_SESSION['user']['id']);

if (empty($cart_items)) {
    header("Location: index.php?page=cart&message=Your cart is empty");
    exit;
}
?>

<div class="row">
    <div class="col">
        <div class="card">
            <h1>Checkout</h1>

            <h3>Billing Information</h3>
            <form id="checkoutForm">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['user']['name']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" required></textarea>
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" required>
                </div>

                <h3 style="margin-top: 2rem;">Payment Method</h3>

                <div class="form-group">
                    <label for="payment_method">Choose Payment Method</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="">Select a payment method</option>
                        <option value="cash_on_delivery">Cash on Delivery</option>
                        <option value="bkash">Bkash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                        <option value="card">Credit/Debit Card</option>
                    </select>
                </div>

                <div id="cardFields" style="display: none;">
                    <div class="form-group">
                        <label for="card_number">Card Number</label>
                        <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456">
                    </div>
                    <div class="form-group">
                        <label for="card_expiry">Expiry Date</label>
                        <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY">
                    </div>
                    <div class="form-group">
                        <label for="card_cvv">CVV</label>
                        <input type="text" id="card_cvv" name="card_cvv" placeholder="123" maxlength="3">
                    </div>
                </div>

                <button type="submit" class="btn" style="width: 100%; padding: 1rem; margin-top: 1rem;">Place Order</button>
            </form>
        </div>
    </div>

    <div class="col sidebar">
        <div class="card">
            <h3>Order Summary</h3>
            
            <div style="margin-bottom: 1rem; max-height: 300px; overflow-y: auto; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                <?php foreach ($cart_items as $item): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 14px;">
                        <span><?php echo htmlspecialchars($item['title']); ?> × <?php echo $item['quantity']; ?></span>
                        <span>৳<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid #eee;">
                <span>Subtotal:</span>
                <span>৳<?php echo number_format($total, 2); ?></span>
            </div>

            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #eee;">
                <span>Shipping:</span>
                <span>৳0.00</span>
            </div>

            <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold;">
                <span>Total:</span>
                <span>৳<?php echo number_format($total, 2); ?></span>
            </div>
        </div>
    </div>
</div>

<script>
    const paymentMethod = document.getElementById('payment_method');
    const cardFields = document.getElementById('cardFields');

    paymentMethod.addEventListener('change', function() {
        if (this.value === 'card') {
            cardFields.style.display = 'block';
        } else {
            cardFields.style.display = 'none';
        }
    });

    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('../Controller/checkout-process.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'index.php?page=order-success&order_id=' + data.order_id;
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred. Please try again.', 'error');
        });
    });
</script>

<?php include 'footer.php'; ?>
