<?php

namespace App\Controller;


use App\Entity\Secretaire;
use App\Form\SecretaireType;
use App\Repository\SecretaireRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
/**
 * @Route("/secretaire")
 * @IsGranted({"ROLE_SECRETAIRE","ROLE_ETABLISSEMENT"})
 */
class SecretaireController extends AbstractController {

    // Show DirecteurProfile from Etablissment Account
    /**
     * @Route("/", name="secretaire_index", methods={"GET"})
     * @IsGranted({"ROLE_ETABLISSEMENT"})
    */
    public function index(SecretaireRepository $secretaire): Response
    {
        return $this->render('secretaire/index.html.twig', [
            'secretaires' => $secretaire->findByEtablissement(['etablissement_id' => $this->getUser()])
        ]);
    }

    /**
     * @Route("/profile/{id}", name="secretaire_show", methods={"GET"})
     */
    public function show(Secretaire $secretaire): Response {
        return $this->render('secretaire/show.html.twig', [
            'secretaire' => $secretaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="secretaire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Secretaire $secretaire): Response
    {
        $form = $this->createForm(SecretaireType::class, $secretaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['photoUrl']->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try{
                $file->move($this->getParameter('image_directory'),$fileName);
            }catch (FileException $e){

            }

            $secretaire->setPhotoUrl($fileName);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le compte a été modifié avec succes');

            return $this->redirectToRoute('secretaire_show',array('id' => $secretaire->getId()));
        }

        return $this->render('secretaire/edit.html.twig', [
            'secretaire' => $secretaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="secretaire_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Secretaire $secretaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$secretaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $secretaire->setDeleted(1);
            // $entityManager->remove($secretaire);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_logout');
        }

        return $this->redirectToRoute('secretaire_show',array('id' => $secretaire->getId()));
    }

    /**
     * @Route("/desactivate/{id}", name="secretaire_desactivate", methods={"DELETE"})
     * @IsGranted({"ROLE_ETABLISSEMENT"})
     */
    public function desactivate(Request $request, Secretaire $secretaire): Response
    {
        if ($this->isCsrfTokenValid('desactivate'.$secretaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $secretaire->setDeleted(1);
            $entityManager->flush();
            $this->addFlash('success', 'Le compte a été desactiver avec success');
            return $this->redirectToRoute('secretaire_index');
        }

        $this->addFlash('error', 'there is something wrong, -_- Pls try Again later');
        return $this->redirectToRoute('secretaire_index');
    }

    /**
     * @Route("/activate/{id}", name="secretaire_activate", methods={"DELETE"})
     * @IsGranted({"ROLE_ETABLISSEMENT"})
     */
    public function activate(Request $request, Secretaire $secretaire): Response
    {
        if ($this->isCsrfTokenValid('activate'.$secretaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $secretaire->setDeleted(0);
            $entityManager->flush();
            $this->addFlash('success', 'Le compte a été Activer avec success');
            return $this->redirectToRoute('secretaire_index');
        }

        $this->addFlash('error', 'there is something wrong, -_- Pls try Again later');
        return $this->redirectToRoute('secretaire_index');
    }

}
