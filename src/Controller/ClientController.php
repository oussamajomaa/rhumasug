<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="client")
     */
    public function index(Request $request)
    {
        $client=$this->getUser();
        // si la methode du formulaire est 'POST'
        if ($request->getMethod()=='POST'){
            $client->setNom($request->get('nom'));
            $client->setPrenom($request->get('prenom'));
            $client->setTel($request->get('tel'));
            $client->setAdresse($request->get('adresse'));
            $client->setVille($request->get('ville'));
            $client->setPays($request->get('pays'));
            
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            return $this->render('client/client.html.twig', [
                'message' => "Votre profil a été mis à jour."
            ]);
        }
        return $this->render('client/client.html.twig');
    }

    /**
     * @Route("/mdp", name="mdp")
     */
    public function motDePasse(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getuser();
        if ($user){
            if ($request->getMethod() == 'POST') {
                $old_pwd = $request->get('old_pwd');
                $new_pwd = $request->get('new_pwd');
                $confirm_pwd = $request->get('confirm_pwd');
                // dd($confirm_pwd);
                if ($confirm_pwd==$new_pwd){
                    $checkPass = $encoder->isPasswordValid($this->getUser(), $old_pwd);
                    if ($checkPass) {
                        $new_pwd_encode = $encoder->encodePassword($this->getUser(), $new_pwd);
                        $manager = $this->getDoctrine()->getManager();
                        $user->setPassword($new_pwd_encode);
                        $manager->flush();
                        return $this->render('client/client.html.twig', [
                            'message' => "Votre mot de passe a été changé. Connextez-vous de nouveau S.V.P"
                        ]);
                    }
                    else{
                        return $this->render('client/client.html.twig',[
                            'message'=>"L'ancien mot de passe est incorrect !!"
                        ]);
                    }
                }
                else{
                    return $this->render('client/client.html.twig', [
                        'message' => "Les mots de passe ne sont pas identiques !!"
                    ]); 
                }
            }
            return $this->render('client/client.html.twig');
        }   
    }
}
