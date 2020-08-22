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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/laureat")
 * @IsGranted({"ROLE_LAUREAT"})
 */
class LaureatController extends AbstractController
{
    /**
     * @Route("/", name="laureat_index", methods={"GET"})
     */
    public function index(LaureatRepository $laureatRepository): Response
    {
        return $this->render('laureat/index.html.twig', [
            'laureats' => $laureatRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="laureat_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $laureat = new Laureat();
        $form = $this->createForm(LaureatType::class, $laureat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($laureat);
            $entityManager->flush();

            return $this->redirectToRoute('laureat_index');
        }

        return $this->render('laureat/new.html.twig', [
            'laureat' => $laureat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="laureat_show", methods={"GET"})
     */
    public function show(Laureat $laureat): Response
    {
        return $this->render('laureat/show.html.twig', [
            'laureat' => $laureat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="laureat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Laureat $laureat): Response
    {
        $form = $this->createForm(LaureatType::class, $laureat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['photoUrl']->getData();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try{
                $file->move($this->getParameter('image_directory'),$fileName);
            }catch (FileException $e){}

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('laureat_index');
        }

        return $this->render('laureat/edit.html.twig', [
            'laureat' => $laureat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="laureat_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Laureat $laureat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$laureat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($laureat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('laureat_index');
    }

    /**
     * @Route("/profildip/{id}", name="laureat_diplome", methods={"GET"})
     */
    public function profildiplome(Laureat $laureat):Response
    {

        return $this->render('laureat/profilDip.html.twig',[
          'laureat' => $laureat
        ]);
    }


    // Rechercher un etablissement + envoyer une demande (diplome)
    /**
 * @Route("/profiletab/{id}", name="laureat_etablissement", methods={"GET","POST"})
 */
    public function profiletablissement(Laureat $laureat,Request $request,EtablissementRepository $etablissementRepository,SecretaireRepository $secretaireRepository):Response
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
            $etablissementss = $repository->find($id[0]);

            $demande->setEtablissement($etablissementss);
            $demande->setDiplome($diplome);
            $demande->setLaureat($laureat);
            

            $entityManager->persist($demande);
            $entityManager->flush();

            // L'envoi d'un Email au secretaire 
            /*
            $secretaire = new Secretaire();
            $email = $secretaireRepository->findEmail($id[0]);
            $message = (new \Swift_Message('Nouvelle demande !'))
            ->setFrom('')
            // email de la secretaire
            ->setTo('')
            ->setBody("Un laureat à déposé sa demande");
            $mailer->send($message);
            */
            $this->addFlash('success', 'Demande ajoutée avec succes');

           }
            else{
                $this->addFlash('erreur', 'Erreur, probléme de format');
            }
            return $this->redirectToRoute('laureat_etablissement',array(
                'id' => $laureat->getId()));
        }


        return $this->render('laureat/profilEtab.html.twig',[
            'laureat' => $laureat,
            'etablissements' => $etablissements,
            'name' => $name,
            'form' => $form->createView()

        ]);
    }


    /**
     * @Route("/profilmodif/{id}", name="laureat_modif", methods={"GET","POST"})
     */
    public function profilmodification(Laureat $laureat,Request $request,EtablissementRepository $etablissementRepository):Response
    {
        $form = $this->createForm(LaureatType::class, $laureat);
        $form->handleRequest($request);

        return $this->render('laureat/profilModif.html.twig', [
            'laureat' => $laureat,
            'form' => $form->createView(),
        ]);

    }

    // Modifier le profil de laureat
    /**
     * @Route("/profilmodifier/{id}", name="laureat_modifier", methods={"GET","POST"})
     */
    public function profilmodifier(Laureat $laureat,Request $request):Response
    {
        $form = $this->createForm(LaureatType::class, $laureat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['photoUrl']->getData();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try{
                $file->move($this->getParameter('image_directory'),$fileName);
            }catch (FileException $e){}

            $laureat->setPhotoUrl($fileName);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le compte a été modifié avec succes');

            return $this->redirectToRoute('laureat_apropos',array(

                'id' => $laureat->getId()));
        }


    }

    /**
     * @Route("/profilapropos/{id}", name="laureat_apropos", methods={"GET"})
     */
    public function profilapropos(Laureat $laureat):Response
    {
        return $this->render('laureat/profilApropos.html.twig',[
            'laureat' => $laureat
        ]);
    }


    // Désactiver compte laureat
    /**
     * @Route("/profildesactiver/{id}", name="laureat_desactiver", methods={"GET","POST"})
     */
    public function profildesactiver(Laureat $laureat,Request $request):Response
    {
        $action=$request->request->get("desac");

        if($action)
        {
            // Exemple : 1 = compte désactiver
        $laureat->setDeleted(1);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($laureat);
        $entityManager->flush();


        return $this->render('laureat/profilDip.html.twig',[
            'laureat' => $laureat,
        ]);
        }
        return $this->render('laureat/profilDesactiver.html.twig',[
            'laureat' => $laureat,
        ]);
    }
}

