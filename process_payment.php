<?php
require_once 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_YOUR_SECRET_KEY'); // Replace with your secret key

$data = json_decode(file_get_contents('php://input'), true);

try {
    $charge = \Stripe\Charge::create([
        'amount' => $data['amount'], // Amount in cents
        'currency' => 'usd',
        'source' => $data['stripeToken'],
        'description' => 'Healthy Cart Order - ' . $data['email'],
        'metadata' => [
            'email' => $data['email'],
            'name' => $data['name'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'zip' => $data['zip'],
            'country' => $data['country']
        ]
    ]);

    echo json_encode(['success' => true, 'chargeId' => $charge->id]);
} catch (\Stripe\Exception\CardException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Payment processing error']);
}
?>
