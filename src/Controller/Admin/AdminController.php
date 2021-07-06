<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Http\Cookie\Cookie;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Security;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpClient\HttpClient;
use Wsi\Utils\AdminAuth;
use Wsi\Utils\EmailSignature;

class AdminController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('admin');
    }

    public function index()
    {
        dd('Admin area');
    }

    public function createPaymentLink()
    {
        $postData = $this->request->getParsedBody();
        if (isset($postData['create_link'])) {

            $paymentRequestsTable = TableRegistry::getTableLocator()->get('PaymentRequests');
            $paymentRequest = $paymentRequestsTable->newEmptyEntity();

            $paymentRequest->amount = (float) $postData['amount'];
            $paymentRequest->email = $postData['email'];
            $paymentRequest->token = Security::randomString(12);
            $paymentRequest->card_type_id = $postData['card_type'];
            $paymentRequest->installments = $postData['installments'];

            if ($paymentRequest = $paymentRequestsTable->save($paymentRequest)) {
                $mailer = new Mailer('default');

                $mailer
                    ->setEmailFormat('html')
                    ->setTo($postData['email'])
                    ->setSubject('[Casiraghi Hnos] Finalizá tu compra')
                    ->viewBuilder()
                    ->setTemplate('Payments/payment_request')
                    ->setVars(
                        [
                            'url' => Configure::read('App.fullBaseUrl') . 'pagar/link/' . $paymentRequest->token,
                            'amount' => $paymentRequest->amount,
                            'installments' => $paymentRequest->installments,
                        ]
                    );

                try {
                    $mailer->deliver();
                    $this->set('success', true);
                } catch (\Throwable $th) {
                    $this->set('success', false);
                }
            }
        }
    }

    public function login()
    {
        if ($this->request->getMethod() == 'POST') {
            $password = Hash::get($this->request->getParsedBody(), 'password');
            $path = Hash::get($this->request->getParsedBody(), 'path');

            if (!($password === Configure::read('Security.admin_password'))) {
                header('Location: /admin/login?path=' . $path);
                die;
            }
            return $this->response->withCookie(AdminAuth::getAdminCookie())->withAddedHeader('Location', $path);
        }
        $this->set('path', $this->request->getQuery('path'));
        return $this->render('admin_password');
    }

    public function createSignature()
    {
        if ($this->request->getMethod() == 'POST') {
            $data = $this->request->getParsedBody();
            $data['pic'] = $this->request->getUploadedFile('pic');
            $htmCode = EmailSignature::createSignature($data);

            return $this->response
                ->withDownload($data['name'] . ' - ' . EmailSignature::$theme . '.htm')
                ->withStringBody($htmCode);
        }

        return $this->render('create_signature');
    }

    public function syncFlexxusForm()
    {
        if ($this->request->getMethod() == 'POST') {
            $client = new Client();

            $result = $client->get('https://express.casiraghi.com.ar/tienda/module/syncflexxus/sync?p=true', [], ['timeout' => 1800]);

            $this->set('result', $result->getBody()->getContents());
        }

        return $this->render('sync_flexxus_form');
    }
}
