<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .invoice-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .invoice-header h2 {
            margin: 0;
        }
        .invoice-header p {
            margin: 5px 0;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .invoice-table th {
            background-color: #f2f2f2;
        }
        .invoice-total {
            display: flex;
            justify-content: flex-end;
        }
        .invoice-total p {
            margin-right: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div>
                <h2>Invoice</h2>
                <p>Invoice Number: INV-12345</p>
                <p>Date: April 4, 2024</p>
            </div>
            <div>
                <p>Customer: John Doe</p>
                <p>Email: john@example.com</p>
            </div>
        </div>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Product 1</td>
                    <td>2</td>
                    <td>$10.00</td>
                    <td>$20.00</td>
                </tr>
                <tr>
                    <td>Product 2</td>
                    <td>1</td>
                    <td>$15.00</td>
                    <td>$15.00</td>
                </tr>
            </tbody>
        </table>
        <div class="invoice-total">
            <p><strong>Subtotal:</strong> $35.00</p>
            <p><strong>Tax (10%):</strong> $3.50</p>
            <p><strong>Total:</strong> $38.50</p>
        </div>
    </div>
    <div class="footer">
        <p>Thank you for your business!</p>
    </div>
</body>
</html>
