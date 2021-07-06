<?php

namespace App\Controller;

use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;
use Decidir\Connector;

class CheckoutController extends AppController
{

    public function displayCheckout()
    {
        return $this->render('checkout');
    }

    public function displayFormCardDetails($token)
    {
        $paymentRequestRecord = TableRegistry::getTableLocator()->get('PaymentRequests')->query()->where(['token' => $token])->first();

        if (!$paymentRequestRecord) {
            $this->set(['error' => true]);
            return $this->render('card_details', 'admin');
        }

        $amount = $paymentRequestRecord->amount;
        $installments = $paymentRequestRecord->installments;

        $this->set(compact(['token', 'amount', 'installments']));
        return $this->render('card_details', 'admin');
    }

    public function processPayment()
    {
        $keys = [
            'public_key' => '96e7f0d36a0648fb9a8dcb50ac06d260',
            'private_key' => '1b19bb47507c4a259ca22c12f78e881f',
        ];
        $decidir = new Connector($keys, 'test');

        $requestData = json_decode($this->request->getData('jsonData'), true);

        $token = $this->request->getData('token');

        $paymentRequestRecord = TableRegistry::getTableLocator()->get('PaymentRequests')->query()->where(['token' => $token])->first();

        $clientEmail =  $paymentRequestRecord->email;
        $amount =       $paymentRequestRecord->amount;
        $card_type_id = $paymentRequestRecord->card_type_id;
        $installments = $paymentRequestRecord->installments;
        $clientName =   $requestData['cardholder']['name'];

        $paymentData = [
            'site_transaction_id' => 'test_' . bin2hex(random_bytes(4)),
            'token' => $requestData['id'],
            'customer' => [
                'id' => 'test_wsi_2',
                'email' => $clientEmail,
            ],
            'payment_method_id' => (int) $card_type_id,
            'bin' => $requestData['bin'],
            'amount' => $amount,
            'currency' => 'ARS',
            'installments' => (int) $installments,
            'description' => 'testeo',
            'establishment_name' => 'Casiraghi Hnos',
            'payment_type' => 'single',
            'sub_payments' => [],
        ];
        try {
            $paymentResult = $decidir->payment()->ExecutePayment($paymentData);
        } catch (\Throwable $th) {
            dd($paymentResult);
        }

        $paymentRequestsTable = TableRegistry::getTableLocator()->get('PaymentRequests');
        $paymentRequestsTable->delete($paymentRequestRecord);

        $mailerClient = new Mailer('default');

        $mailerClient
            ->setEmailFormat('html')
            ->setTo($clientEmail)
            ->setSubject('[Casiraghi Hnos] Gracias por tu compra')
            ->viewBuilder()
            ->setTemplate('Payments/purchase_receipt')
            ->setVars(['amount' => $amount, 'installments' => $installments]);

        $mailerClient->deliver();

        $mailerCasi = new Mailer('default');

        $mailerCasi
            ->setEmailFormat('html')
            ->setTo('ventas@casiraghi.com.ar')
            ->setSubject('[Casiraghi Express] Un cliente realizó un pago')
            ->viewBuilder()
            ->setTemplate('Admin/payments/payment_success')
            ->setVars(
                [
                    'clientName' => $clientName,
                    'amount' => $amount,
                    'installments' => $installments,
                    'clientEmail' => $clientEmail
                ]
            );

        $mailerCasi->deliver();

        return $this->response->withStringBody($clientEmail);
    }

    public function displayThankyouPage()
    {
        $email = $this->request->getQuery('email');

        $this->set('email', $email);

        return $this->render('thankyou');
    }
}
