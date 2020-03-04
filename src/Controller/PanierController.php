<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
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
        $qte = $request->get('qte');
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
        return $this->json([
            'message' => 'Un produit a été supprimé du panier',
            'count' => $repo->count(['user' => $this->getUser()])

        ]);
        // return $this->redirectToRoute('panier');
    }


    /**
     * @Route("/panier/{id}", name="addPanier")
     */
    public function add(Request $request, Produit $produit)
    {
        // dd($request->get("qte"));
        // Ajouter un produit au panier
        $panier = new Panier();

        $qte = $request->get('qte');
        if ($this->getUser()) {
            $manager = $this->getDoctrine()->getManager();
            $item = $manager->getRepository(Panier::class)->findOneBy(['user' => $this->getUser(), 'produit' => $produit]);
            if ($item) {
                $qte = $item->getQte() + $request->get('qte');
                $panier = $manager->getRepository(Panier::class)->find($item->getId());

                $panier->setQte($qte);
                $manager->flush();
            } else {

                $produit = $manager->getRepository(Produit::class)->find($produit);

                $panier->setUser($this->getUser())
                    ->setProduit($produit)
                    ->setQte($qte);
                $manager->persist($panier);
                $manager->flush();
            }
        }
        $repo = $this->getDoctrine()->getRepository(Panier::class);
        return $this->json([
            'message' => 'Un produit a été ajouté au panier',
            'count' => $repo->count(['user' => $this->getUser()])
        ]);
        // return $this->redirectToRoute('accueil');
    }


    /**
     * @Route("/panier", name="panier")
     */
    public function index(Request $request)
    {
        // Afficher tous les produits
        $repo = $this->getDoctrine()->getRepository(Panier::class);
        $session = $request->getSession();
        $session->set('no', $repo->count(['user' => $this->getUser()]));
        $allpaniers = $repo->findBy(["user" => $this->getUser()]);
        if ($allpaniers) {

            return $this->render('panier/index.html.twig', [
                'paniers' => $allpaniers
            ]);
        } else {
            return $this->redirectToRoute('accueil');
        }
    }

    public function total()
    {
        $repo = $this->getDoctrine()->getRepository(Panier::class);
        $paniers = $repo->findBy(["user" => $this->getUser()]);
        $total = 0;
        foreach ($paniers as $row) {
            $total += ($row->getProduit()->getPrix()) * ($row->getQte());
        }
        // sotre the number of row in cart in session variable
        $session = $request->getSession();
        $session->set('no', $repo->count(['user' => $this->getUser()]));
        $session->set('total', $total);
    }
}
