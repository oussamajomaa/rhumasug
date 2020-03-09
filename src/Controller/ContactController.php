<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailerInterface $mailer)
    {
        
        $form=$this->createForm(ContactType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $email = new Email();
            $data=$form->getData();
            // dd($request->get('_token'));
            $secret= '6LcbuN8UAAAAAG8WNPWnJpBKbCBf1bfWks1H1VC6';
            $response = $request->get('g-recaptcha-response');

            $email->from($data['email'])
                  ->to("oussama1970jomaa@gmail.com")
                  ->subject('message')
                  ->html($data['message']);
            $mailer->send($email);
        }
        
        return $this->render('contact/contact.html.twig', [
            'formContact' => $form->createView()
        ]);
    }
}
