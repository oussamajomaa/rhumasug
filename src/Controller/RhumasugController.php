<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\User;
use App\Form\PanierType;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\EntityManagerInterface;
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
        
        // Compter le nombre du produit dand le panier
        
        // afficher tous les produits dans la page accueil
        $repo = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $repo->findAll();

        $repo = $this->getDoctrine()->getRepository(Panier::class);

        // sotre the number of row in cart in session variable
        $session = $request->getSession();
        $session->set('no', $repo->count(['user' => $this->getUser()]));
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
        $produit = $repo->find($id);
        return $this->render('rhumasug/detail_produit.html.twig', [
            'produit' => $produit
        ]);
    }

    /**
     * @Route("/nous", name="nous")
     */
    public function qui_sommes_nous()
    {

        return $this->render('rhumasug/qui_sommes_nous.html.twig');
    }



    /**
     * @Route("/chercher", name="chercher")
     */

    //  chercher un produit par le nome du produit
    public function chercher(request $request)
    {
        
        // récupérer l'input chercher
        $string=$request->get('chercher');

        $repo = $this->getDoctrine()->getManager();
        $produit = $repo->getRepository(Produit::class)->findByString("%".$string."%");
        return $this->render('rhumasug/chercher.html.twig',[
            'produits'=>$produit,
            'message'=>'Aucun produit trouvé correspond à votre recherche !!'
        ]);
    }
}
