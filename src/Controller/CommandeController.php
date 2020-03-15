<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Commande;
use App\Entity\CommandeProduit;
use App\Repository\CommandeProduitRepository;
use App\Repository\PanierRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class CommandeController extends AbstractController
{
    /**
     * @Route("/commande", name="commande")
     */
    public function index(PanierRepository $repo)
    {

        // $repo = $this->getDoctrine()->getRepository(Panier::class);
        // $session = $request->getSession();
        // $session->set('no', $repo->count(['user' => $this->getUser()]));
        $commandes = $repo->findBy(["user" => $this->getUser()]);
        return $this->render('commande/commande.html.twig', [
            'commandes' => $commandes
        ]);
    }

    /**
     * @Route("/paiement", name="paiement")
     */
    public function paiement()
    {


        return $this->render('commande/paiement.html.twig');
    }

    /**
     * @Route("/facture", name="facture")
     */
    public function facture(Request $request)
    {
        

        $repo=$this->getDoctrine()->getRepository(Panier::class);
        $panier=$repo->findBy(['user'=>$this->getUser()]);
        if ($panier){
        if ($this->getUser()) {
                $manager=$this->getDoctrine()->getManager();
                // insert into commande entity
                $commande=new Commande();
                
               
                $commande->setUser($this->getUser())
                        ->setCreatedAt(new \DateTime('now'));
                $manager->persist($commande);
                $manager->flush();

                // insert into commandeProduit entity all products in the cart
                foreach ($panier as $item){
                    $commandeProduit=new CommandeProduit();
                    $commandeProduit->setProduit($item->getProduit())
                                    ->setPrix($item->getProduit()->getPrix())
                                    ->setCommande($commande)
                                    ->setQte($item->getQte());
                                    
                    $manager->persist($commandeProduit);
                }
    
                // empty the cart
                foreach ($panier as $item){
                    $manager->remove($item);
                }
                
                $manager->flush();    
            }
            $session = $request->getSession();
            $session->set('commande',$commande);
            
            $repo = $this->getDoctrine()->getRepository(CommandeProduit::class);
            $commandesProduits = $repo->findBy(['commande' => $commande]);
            return $this->render('commande/facture.html.twig',[
                'commandesProduits'=>$commandesProduits,
                'commande'=>$commande
            ]);
            // return $this->redirectToRoute('pdfFacture');
        }
        return $this->redirectToRoute('accueil');

    }

    
    /**
     * @Route("/pdfFacture", name="pdfFacture")
     */
    public function pdfFacture(Request $request)
    {
        $session = $request->getSession();
        $commande= $session->get('commande');
        $repo = $this->getDoctrine()->getRepository(CommandeProduit::class);
        $commandesProduits = $repo->findBy(['commande' => $commande]);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('commande/pdfFacture.html.twig', [
            'commandesProduits' => $commandesProduits,
            'commande' => $commande
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        
        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
        return $this->redirectToRoute('accueil');
        
    }

    /**
     * @Route("/mesCommandes", name="mesCommandes")
     */
    public function mesCommandes()
    {
        if ($this->getUser()) {
            $repo = $this->getDoctrine()->getRepository(Commande::class);
            $commandes = $repo->findBy(['user' => $this->getUser()]);

            $repo1 = $this->getDoctrine()->getRepository(CommandeProduit::class);
            $commandeProduits = [];
            // dump($commandes);
            foreach ($commandes as $commande) {
                array_push($commandeProduits, $repo1->findby(['commande' => $commande]));
            }

            return $this->render('commande/mesCommandes.html.twig', [
                'mesCommandes' => $commandeProduits,

            ]);
        }
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/printCommande/{id}", name="printCommande")
     */
    public function printCommande(CommandeProduitRepository $repo, $id)
    {
        $commande=$repo->findBy(['commande'=>$id]);
        // return $this->render('commande/printCommande.html.twig',[
        //     'commandesProduits'=>$commande
        // ]);
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('commande/printCommande.html.twig', [
            'commandesProduits' => $commande
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);

    }
   
}
