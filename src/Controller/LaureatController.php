<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\Diplome;
use App\Entity\Etablissement;
use App\Entity\Laureat;
use App\Entity\Secretaire;
use App\Form\LaureatType;
use App\Form\DiplomeType;
use App\Form\SecretaireType;
use App\Form\EtablissementType;
use App\Repository\EtablissementRepository;
use App\Repository\SecretaireRepository;
use App\Repository\LaureatRepository;
use App\Repository\DiplomeRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/laureat")
 * @IsGranted({"ROLE_LAUREAT", "ROLE_ETABLISSEMENT"})
 */
class LaureatController extends AbstractController
{

    // Show laureat Profile from Etablissment Account
    /**
     * @Route("/", name="laureat_index", methods={"GET"})
     * @IsGranted({"ROLE_ETABLISSEMENT"})
    */
    public function index(LaureatRepository $laureatRepository): Response
    {
        return $this->render('laureat/index.html.twig', [
            'laureats' => $laureatRepository->findAll(),
        ]);
    }

    // Add New Laureta Account from Etablissment Account
    /**
     * @Route("/new", name="laureat_new", methods={"GET","POST"})
     * @IsGranted({"ROLE_ETABLISSEMENT"})
    */
    public function new(Request $request): Response
    {
        $laureat = new Laureat();
        $form = $this->createForm(LaureatType::class, $laureat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
        }
        return $this->render('laureat/new.html.twig', [
            'laureat' => $laureat,
            'form' => $form->createView(),
        ]);
    }

    // Show Laureat Diplomes
    /**
     * @Route("/profildip/{id}", name="laureat_diplome", methods={"GET"})
     * @IsGranted({"ROLE_LAUREAT"})
    */
    public function profildiplome(Laureat $laureat,LaureatRepository $laureatRepository,$id):Response
    {
        $diplomes = $laureatRepository->getDiplomesAuth($id);

        //dd($diplomes);
        if (empty($diplomes)) {
            $verif = 0;
        } else {
            $verif = 1;
        }

        return $this->render('laureat/profilDip.html.twig',[
            'laureat' => $laureat,
            'diplomesAuth' => $diplomes,
            'verif' => $verif
        ]);
    }

    // Rechercher un etablissement + envoyer une demande (diplome)
    /**
     * @Route("/profiletab/{id}", name="laureat_etablissement", methods={"GET","POST"})
     * @IsGranted({"ROLE_LAUREAT"})
     */
    public function profiletablissement(Laureat $laureat,Request $request,EtablissementRepository $etablissementRepository):Response
    {
        $diplome = new Diplome();

        //Search Field Return Etablissment
        $name = $request->request->get("name");
        $etablissements = $etablissementRepository->findEtablissmentByString($name);
        
        $form = $this->createForm(DiplomeType::class, $diplome);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['fichier']->getData();
            $extension = $file->guessExtension();
            if ($extension == 'pdf')
            {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                try {
                    $file->move($this->getParameter('diplome_directory'), $fileName);
                } catch (FileException $e) {
                    
                }

                // Ajout Diplome
                $diplome->setFichier($fileName);
                $diplome->setDateDepot(new \DateTime('now'));
                $diplome->setCode(uniqid());


                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($diplome);
                $entityManager->flush();

                // Ajout demande
                $demande = new Demande();
                $etablissementId = $request->request->get("id");
                $etablissement = $etablissementRepository->findEtablissementByName($etablissementId);
                // dd($id,$nomEtablissement,$request);
                // die();
                // $repository = $this->getDoctrine()->getManager()->getRepository(Etablissement::class);
                // $etablissementss = $etablissementRepository->findOneBy(['id' => $id]);
                
                $demande->setEtablissement($etablissement);
                $demande->setDiplome($diplome);
                $demande->setLaureat($laureat);
            
                $entityManager->persist($demande);
                $entityManager->flush();

                $this->addFlash('succes', 'Demande ajoutée avec succes');

            }
            else{
                $this->addFlash('erreur', 'Erreur, probléme de format');
            }

            return $this->redirectToRoute('laureat_etablissement', array('id' => $laureat->getId()));
        }
        
        return $this->render('laureat/profilEtab.html.twig',[
            'laureat' => $laureat,
            'etablissements' => $etablissements,
            'name' => $name,
            'form' => $form->createView()
        ]);
    }

    // Edit Laureat Account
    /**
     * @Route("/profilmodif/{id}", name="laureat_modif", methods={"GET","POST"})
     * @IsGranted({"ROLE_LAUREAT"})
     */
    public function profilmodification(Laureat $laureat,Request $request,EtablissementRepository $etablissementRepository):Response
    {
        $form = $this->createForm(LaureatType::class, $laureat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['photoUrl']->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try{
                $file->move($this->getParameter('image_directory'),$fileName);
            }catch (FileException $e){

            }

            $laureat->setPhotoUrl($fileName);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le compte a été modifié avec succes');
            return $this->redirectToRoute('laureat_apropos',array('id' => $laureat->getId()));
        }

        return $this->render('laureat/profilModif.html.twig', [
            'laureat' => $laureat,
            'form' => $form->createView(),
        ]);

    }

    //  Laureat Account Information
    /**
     * @Route("/profilapropos/{id}", name="laureat_apropos", methods={"GET"})
     * @IsGranted({"ROLE_LAUREAT"})
     */
    public function profilapropos(Laureat $laureat):Response
    {
        return $this->render('laureat/profilApropos.html.twig',['laureat' => $laureat]);
    }

    // Delete Laeaureat Account permanently 
    /**
    * @Route("/{id}", name="laureat_delete", methods={"DELETE"})
    */
    public function delete(Request $request, Laureat $laureat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$laureat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $laureat->setDeleted(1);
            $entityManager->flush();
            return $this->redirectToRoute('app_logout');
        }

        return $this->render('laureat/profilApropos.html.twig',['laureat' => $laureat]);
    }
}

