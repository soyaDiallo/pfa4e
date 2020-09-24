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
    public function profiletablissement(Laureat $laureat,Request $request,EtablissementRepository $etablissementRepository,\Swift_Mailer $mailer,UserRepository $userRepository):Response
    {
        $diplome = new Diplome();
        $name = $request->request->get("name");

        $repository = $this->getDoctrine()->getManager()->getRepository(Etablissement::class);
        $etablissements = $repository->findBy(
            ['nomEtablissement' => $name]
        );

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
                $diplome->setCode(md5(uniqid()));


                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($diplome);
                $entityManager->flush();

                // Ajout demande
                $demande = new Demande();
                $nomEtablissement = $request->request->get("id");

                $id = $etablissementRepository->findIdEtablissement($nomEtablissement);

                $repository = $this->getDoctrine()->getManager()->getRepository(Etablissement::class);
                $etablissementss = $repository->find(intval($id));

                $demande->setEtablissement($etablissementss);
                $demande->setDiplome($diplome);
                $demande->setLaureat($laureat);
            
                $entityManager->persist($demande);
                $entityManager->flush();


                /*
                //Notifier la Secretaire par un mail
                $email = $userRepository->findEmail(intval($id));
                $message = (new \Swift_Message('Nouvelle demande !'))
                ->setFrom('kribiazakaria1@gmail.com')
                // email de la secretaire
                ->setTo($email)
                ->setBody('Vous avez reçu une nouvelle demande, vérifiez votre espace !');
                $mailer->send($message);


                // Notifier le directeur pedagogique par un mail
                $email = $userRepository->findEmailDir(intval($id));
                $message = (new \Swift_Message('Nouvelle demande peda !'))
                ->setFrom('kribiazakaria1@gmail.com')
                // email directeur pedagogique
                ->setTo($email)
                ->setBody('Vous avez reçu une nouvelle demande, vérifiez votre espace !');
                $mailer->send($message);

                // Notifier l'Etablissement par un mail
                $email = $userRepository->findEmailEtab(intval($id));
                $message = (new \Swift_Message('Nouvelle demande etab !'))
                    ->setFrom('kribiazakaria1@gmail.com')
                    // email Etablissement
                    ->setTo($email)
                    ->setBody('Vous avez reçu une nouvelle demande, vérifiez votre espace !');
                $mailer->send($message);

                */


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

