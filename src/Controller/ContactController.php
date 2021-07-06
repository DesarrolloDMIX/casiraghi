<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Mailer\Mailer;

class ContactController extends AppController
{
    public function send()
    {
        $data = $this->request->getParsedBody();

        $mailerCasi = new Mailer('default');

        $mailerCasi
            ->setEmailFormat('html')
            ->setTo('ventas@casiraghi.com.ar')
            ->setSubject('El cliente ' . $data['name'] . ' ' . $data['lastName'] . ' se contactó a través de Casiraghi Express.')
            ->viewBuilder()
            ->setTemplate('Contact/client_contact')
            ->setVars(
                $this->request->getParsedBody()
            );

        $mailerCasi->deliver();

        return $this->render('/Pages/gracias-por-contactarnos');
    }
}
