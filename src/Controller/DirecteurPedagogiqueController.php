<?php

namespace App\Controller;

use App\Entity\DirecteurPedagogique;
use App\Form\DirecteurPedagogiqueType;
use App\Repository\DirecteurPedagogiqueRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/directeurPedagogique")
 * @IsGranted({"ROLE_DIRECTEUR","ROLE_ETABLISSEMENT"})
 */
class DirecteurPedagogiqueController extends AbstractController
{

    // Show DirecteurProfile from Etablissment Account
    /**
     * @Route("/", name="directeur_pedagogique_index", methods={"GET"})
     * @IsGranted({"ROLE_ETABLISSEMENT"})
    */
    public function index(DirecteurPedagogiqueRepository $directeurPedagogique): Response
    {
        return $this->render('directeur_pedagogique/index.html.twig', [
            'directeurs_pedagogiques' => $directeurPedagogique->findAll(),
        ]);
    }

    // Add New Directeur Account from Etablissment Account
    /**
     * @Route("/new", name="directeur_pedagogique_new", methods={"GET","POST"})
     * @IsGranted({"ROLE_ETABLISSEMENT"})
    */
    public function new(Request $request): Response
    {
        $directeur = new DirecteurPedagogique();
        $form = $this->createForm(DirecteurPedagogiqueType::class, $directeur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($request);
        }
        return $this->render('directeur_pedagogique/new.html.twig', [
            'directeurs_pedagogiques' => $directeur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/{id}", name="directeur_pedagogique_show", methods={"GET"})
     */
    public function show(DirecteurPedagogique $directeurPedagogique): Response
    {
        return $this->render('directeur_pedagogique/show.html.twig', [
            'directeur_pedagogique' => $directeurPedagogique,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="directeur_pedagogique_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DirecteurPedagogique $directeurPedagogique): Response {
        $form = $this->createForm(DirecteurPedagogiqueType::class, $directeurPedagogique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $file = $form['photoUrl']->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try{
                $file->move($this->getParameter('image_directory'),$fileName);
            }catch (FileException $e){

            }

            $directeurPedagogique->setPhotoUrl($fileName);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le compte a été modifié avec succes');
            return $this->redirectToRoute('directeur_pedagogique_show',array('id' => $directeurPedagogique->getId()));
        }

        return $this->render('directeur_pedagogique/edit.html.twig', [
            'directeur_pedagogique' => $directeurPedagogique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="directeur_pedagogique_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DirecteurPedagogique $directeurPedagogique): Response
    {
        if ($this->isCsrfTokenValid('delete'.$directeurPedagogique->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $directeurPedagogique->setDeleted(1);
            // $entityManager->remove($directeurPedagogique);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_logout');
        }

        return $this->redirectToRoute('directeur_pedagogique_show',array('id' => $directeurPedagogique->getId()));
    }
}
