<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\User;
use App\Form\PanierType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RhumasugController extends AbstractController
{

    /**
     * @Route("/", name="accueil")
     */
    public function show_produit(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Produit::class);
        $produits=$repo->findAll();

        $panier=new Panier();

        $qte=$request->get('qte');
        $id_user=$request->get('id_user');
        $id_produit=$request->get('id_produit');

        if ($id_user && $id_produit){

            $manager=$this->getDoctrine()->getManager();
            $user=$manager->getRepository(User::class)->find($id_user);
            $produit=$manager->getRepository(Produit::class)->find($id_produit);
            
            $panier->setUser($user)
                    ->setProduit($produit)
                    ->setQte($qte);
            $manager->persist($panier);
            $manager->flush();
                
            return $this->redirectToRoute('panier');
                
        }

        return $this->render('rhumasug/accueil.html.twig', [
            'produits' => $produits
        ]);
    }

    /**
     * @Route("/detail{id}", name="detail")
     */
    public function detail_produit($id)
    {
        $repo = $this->getDoctrine()->getRepository(Produit::class);
        $produit=$repo->find($id);
        return $this->render('rhumasug/detail_produit.html.twig', [
            'produit' => $produit
        ]);
    }

    /**
     * @Route("/nous", name="nous")
     */
    public function qui_sommes_nous()
    {
        $repo = $this->getDoctrine()->getRepository(Produit::class);
        $produits=$repo->findAll();
        return $this->render('rhumasug/qui_sommes_nous.html.twig');
    }

    /**
     * @Route("/panier", name="panier")
     */
    public function panier(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Panier::class);
        $paniers=$repo->findAll();

        $panier=new Panier();
        $id_panier=$request->get('id_panier');
        dump($id_panier);
        $manager=$this->getDoctrine()->getRepository(Panier::class)->find($id_panier);
        $manager->remove();

        return $this->render('rhumasug/panier.html.twig',[
            'paniers'=>$paniers
        ]);
    }

}
