<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\User;
use App\Form\PanierType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{


    /**
     * @Route("/updatePanier{id}", name="updatePanier")
     */

    public function update($id, Request $request)
    {
        // Modifier la quantité d'un produit dans le panier
        $qte=$request->get('qte');
        $manager = $this->getDoctrine()->getManager();
        $panier = $manager->getRepository(Panier::class)->find($id);
        if ($panier) {
            $panier->setQte($qte);
            $manager->flush();
        }
        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/deletePanier{id}", name="deletePanier")
     */

    public function delete($id, Request $request)
    {
        // Supprimer un produit du panier
        $manager = $this->getDoctrine()->getManager();
        $panier = $manager->getRepository(Panier::class)->find($id);
        if ($panier) {
            $manager->remove($panier);
            $manager->flush();
        }
        $repo = $this->getDoctrine()->getRepository(Panier::class);
        $count = $repo->count($this->getUser());

        // sotre the number of row in cart in session variable
        $session = $request->getSession();
        $session->set('no', $count);
        return $this->redirectToRoute('panier');
    }


    /**
     * @Route("/panier{id}", name="addPanier")
     */
    public function add(Request $request,$id)
    {
        // Ajouter un produit au panier
        $panier = new Panier();
        $qte = $request->get('qte');
        if ($this->getUser()) {
            $id_user = $this->getUser()->getId();

            if ($id_user && $id) {
                $manager = $this->getDoctrine()->getManager();
                $item = $manager->getRepository(Panier::class)->findBy(['user' => $this->getUser(), 'produit' => $id]);
                if ($item) {
                    $qte = $item[0]->getQte() + $request->get('qte');
                    $panier = $manager->getRepository(Panier::class)->find($item[0]->getId());

                    $panier->setQte($qte);
                    $manager->flush();
                } else {
                    $user = $manager->getRepository(User::class)->find($id_user);
                    $produit = $manager->getRepository(Produit::class)->find($id);

                    $panier->setUser($user)
                        ->setProduit($produit)
                        ->setQte($qte);
                    $manager->persist($panier);
                    $manager->flush();
                }
            }
        }
        $repo = $this->getDoctrine()->getRepository(Panier::class);
        $count = $repo->count($this->getUser());

        // sotre the number of row in cart in session variable
        $session = $request->getSession();
        $session->set('no', $count);
        return $this->redirectToRoute('panier');

    }


    /**
     * @Route("/panier", name="panier")
     */
    public function index()
    {
        // Afficher tous les produits
        $repo = $this->getDoctrine()->getRepository(Panier::class);
        $allpaniers = $repo->findBy(["user" => $this->getUser()]);
        if ($allpaniers) {

            return $this->render('panier/index.html.twig', [
                'paniers' => $allpaniers
            ]);
        } else {
            return $this->redirectToRoute('accueil');
        }

    }
    
}
