<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="client")
     */
    public function index(Request $request)
    {
        $client=$this->getUser();
        if ($request->getMethod()=='POST'){
            $manager = $this->getDoctrine()->getManager();
            $client->setNom($request->get('nom'));
            $client->setPrenom($request->get('prenom'));
            $client->setTel($request->get('tel'));
            $client->setAdresse($request->get('adresse'));
            $client->setVille($request->get('ville'));
            $client->setPays($request->get('pays'));
            $manager->flush();
        }
        return $this->render('client/index.html.twig');
    }

    /**
     * @Route("/mdp", name="mdp")
     */
    public function motDePasse(Request $request)
    {
        $repo=$this->getDoctrine()->getRepository(User::class);
        if ($request->getMethod() == 'POST'){
            $email=$request->get('email');
            $client=$repo->findOneBy(['email'=>$email]);
            dd($client);
        }
        return $this->render('client/mdp.html.twig');
    }
}
