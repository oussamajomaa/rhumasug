<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    /**
     * @Route("/mailer", name="mailer")
     */
    public function index(MailerInterface $mailer)
    {
        
        $email = new Email();
        $email->from("oussama1970jomaa@gmail.com")
              ->to($this->getUser()->getEmail())
              ->subject('Paiement accepté !')
              ->text("blabla")
              ->html('<p>Bonjour !<br>Votre paiement a été accepté !!</p>');
        $mailer->send($email);
        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }
}
