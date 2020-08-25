<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Form\EtablissementType;
use App\Entity\Demande;
use App\Entity\Secretaire;
use App\Form\DemandeType;
use App\Form\SecretaireType;
use App\Repository\DemandeRepository;
use App\Repository\SecretaireRepository;
use App\Repository\EtablissementRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/etablissement")
 * @IsGranted({"ROLE_ETABLISSEMENT"})
 */
class EtablissementController extends AbstractController
{
    // /**
    // * @Route("/", name="etablissement_index", methods={"GET"})
    // */
    // public function index(EtablissementRepository $etablissementRepository): Response {
    //     return $this->render('etablissement/index.html.twig', [
    //         'etablissements' => $etablissementRepository->findAll(),
    //     ]);
    // }

    // /**
    // * @Route("/new", name="etablissement_new", methods={"GET","POST"})
    // */
    // public function new(Request $request): Response {
    //     $etablissement = new Etablissement();
    //     $form = $this->createForm(EtablissementType::class, $etablissement);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($etablissement);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('etablissement_index');
    //     }

    //     return $this->render('etablissement/new.html.twig', [
    //         'etablissement' => $etablissement,
    //         'form' => $form->createView(),
    //     ]);
    // }

    /**
     * @Route("/profile/{id}", name="etablissement_show", methods={"GET"})
     */
    public function show(Etablissement $etablissement): Response
    {
        return $this->render('etablissement/show.html.twig', [
            'etablissement' => $etablissement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="etablissement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Etablissement $etablissement): Response
    {
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['photoUrl']->getData(); 
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            try{
                $file->move($this->getParameter('image_directory'),$fileName);
            }catch (FileException $e){
                dump($e);
            }

            $etablissement->setPhotoUrl($fileName);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le compte a été modifié avec succes');

            return $this->redirectToRoute('etablissement_show',array('id' => $etablissement->getId()));
        }

        return $this->render('etablissement/edit.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etablissement_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Etablissement $etablissement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etablissement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $etablissement->setDeleted(1);
            $entityManager->flush();
            return $this->redirectToRoute('app_logout');
        }

        return $this->redirectToRoute('etablissement_show',array('id' => $etablissement->getId()));
    }


//     /**
//     * @Route("/index2/demande/{id}", name="etablissement_index2", methods={"GET"})
//     */
//     public function index2(DemandeRepository $demandeRepository,$id): Response
//     {

//         return $this->render('etablissement/index2.html.twig', [
//             'demandes' => $demandeRepository->findBy(['etablissement' => $id]),
//         ]);
//     }

//     /**
//      * @Route("/valide/demande/{id}", name="etablissement_valide", methods={"GET","POST"})
//      */
//     public function valideDemande(DemandeRepository $demandeRepository,EtablissementRepository $etablissementRepository,$id): Response
//     {
//         $date = new \DateTime('now');

//         $demandeRepository->updateDemande($id,$date,1);

//         return $this->render('etablissement/index.html.twig', [
//             'etablissements' => $etablissementRepository->findAll(),
//         ]);

//     }

//     /**
//      * @Route("/profiletab/{id}", name="etablissement_profil", methods={"GET"})
//      */
//     public function profiletablissement(Etablissement $etablissement):Response
//     {

//         return $this->render('etablissement/profilEtab.html.twig',[
//           'etablissement' => $etablissement
//         ]);
//     }

//     /**
//      * @Route("/profilapropos/{id}", name="etablissement_apropos", methods={"GET"})
//      */
//     public function profilapropos(etablissement $etablissement):Response
//     {
//         return $this->render('etablissement/profilApropos.html.twig',[
//             'etablissement' => $etablissement
//         ]);
//     }

//     // Désactiver compte etablissement
//     /**
//      * @Route("/profildesactiver/{id}", name="etablissement_desactiver", methods={"GET","POST"})
//      */
//     public function profildesactiver(etablissement $etablissement,Request $request):Response
//     {
//         $action=$request->request->get("desac");

//         if($action)
//         {
//             // Exemple : 1 = compte désactiver
//         $etablissement->setDeleted(1);

//         $entityManager = $this->getDoctrine()->getManager();
//         $entityManager->persist($etablissement);
//         $entityManager->flush();


//         return $this->render('etablissement/profilEtab.html.twig',[
//             'etablissement' => $etablissement,
//         ]);
//         }
//         return $this->render('etablissement/profilDesactiver.html.twig',[
//             'etablissement' => $etablissement,
//         ]);
//     }

//     /**
//  * @Route("/profildemande/{id}", name="etablissement_demande", methods={"GET","POST"})
//  */
//     public function profildemande(etablissement $etablissement):Response
//     {
        

//         return $this->render('etablissement/profildemande.html.twig',[
//           'etablissement' => $etablissement
//         ]);
//     }
}
