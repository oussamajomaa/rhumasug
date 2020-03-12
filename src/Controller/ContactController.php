<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Services\Recaptcha;
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
    public function index(Request $request, MailerInterface $mailer, Recaptcha $recaptcha)
    {
        
        $form=$this->createForm(ContactType::class);
        $form->handleRequest($request);

        // $secret= '6LcbuN8UAAAAAG8WNPWnJpBKbCBf1bfWks1H1VC6';
        // $recaptcha_response=$request->get('recaptcha_response');
        // $url= 'https://www.google.com/recaptcha/api/siteverify';
        // $verifyResponse=file_get_contents($url.'?secret='.$secret.'&response='.$recaptcha_response);
        // $verifyResponse=json_decode($verifyResponse);
       
        // si le formulaire est soumis et valide et la réponse est true
        if(($form->isSubmitted()) && ($form->isValid()) && ($recaptcha->verifyRecaptcha($request))){

            // $email = new Email();
            // $data=$form->getData();

            // $email->from("oussama1970jomaa@gmail.com")
            //       ->to('osm70@gmx.com')
            //       ->subject('message')
            //       ->html($data['message']);
                  
            // $mailer->send($email);
            return $this->render('contact/contact.html.twig', [
                'formContact' => $form->createView(),
                'message'=>'Votre message a été envoyé.'
            ]); 
            
        }
        
        return $this->render('contact/contact.html.twig', [
            'formContact' => $form->createView()
        ]);
    }
}
