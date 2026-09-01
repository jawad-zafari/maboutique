<?php 
$orderInfo = $data['orderInfo'] ?? []; 
// SÉCURITÉ CRITIQUE : Prévention des attaques PHP Object Injection via unserialize
$cart = !empty($orderInfo['cart_data']) ? unserialize($orderInfo['cart_data'], ['allowed_classes' => false]) : [];

$subTotal = 0;
foreach ($cart as $row) {
    $qty = (int)($row['quantity'] ?? 1);
    $subTotal += ((float)($row['price'] ?? 0)) * $qty;
}
$shippingPrice = (float)($orderInfo['shipping_price'] ?? $orderInfo['post_price'] ?? 0);
$totalAmount = (float)($orderInfo['total_amount'] ?? $orderInfo['total_price'] ?? $orderInfo['amount'] ?? 0);

$invoiceDate = date('Y-m-d');
if (!empty($orderInfo['created_timestamp'])) {
    $invoiceDate = date('Y-m-d', $orderInfo['created_timestamp']);
} elseif (!empty($orderInfo['created_date'])) {
    $invoiceDate = $orderInfo['created_date'];
}
$orderId = (int)($orderInfo['id'] ?? 0);
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <base href="<?= URL ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture N° <?= $orderId ?></title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= URL ?>public/assets/css/main.css">
</head>
<body class="invoice-body">

    <div class="invoice-actions-bar no-print">
        <button type="button" id="btnPrintInvoice" class="btn-admin-primary">
            <i class="fa-solid fa-print" aria-hidden="true"></i> Imprimer cette facture
        </button>
        <button type="button" id="btnBackHistory" class="btn-admin-back">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Fermer / Retour
        </button>
    </div>

    <div class="invoice-container">
        
        <header class="invoice-header">
            <div class="company-info">
                <h2>MA BOUTIQUE</h2>
                <p>123 Avenue du Commerce, 75000 Paris</p>
                <p>SIRET : 123 456 789 00012 | TVA : FR123456789</p>
                <p>Email : contact@maboutique.com</p>
            </div>
            <div class="invoice-details">
                <h1>FACTURE</h1>
                <p><strong>N° Facture :</strong> FACT-<?= $orderId ?></p>
                <p><strong>Date :</strong> <?= $this->e($invoiceDate) ?></p>
                <p><strong>Mode de paiement :</strong> <?= $this->e($orderInfo['payTypeTitle'] ?? 'Carte Bancaire') ?></p>
            </div>
        </header>

        <hr class="invoice-divider">

        <div class="invoice-addresses">
            <div class="address-box">
                <h4>Client & Adresse de livraison :</h4>
                <p><strong>Code client :</strong> #<?= (int)($orderInfo['user_id'] ?? 0) ?></p>
                <p><?= nl2br($this->e($orderInfo['address_data'] ?? 'Adresse non spécifiée')) ?></p>
                <p><strong>Code Postal :</strong> <?= $this->e($orderInfo['postal_code'] ?? '-') ?></p>
                <p><strong>Téléphone :</strong> <?= $this->e($orderInfo['phone'] ?? '-') ?></p>
            </div>
        </div>

       

    <script src="<?= URL ?>public/assets/js/admin_order.js" defer></script>
</body>
</html>