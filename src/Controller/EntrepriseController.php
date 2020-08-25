<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
/**
 * @Route("/entreprise")
 * @IsGranted({"ROLE_ENTREPRISE"})
 */
class EntrepriseController extends AbstractController
{

    /**
    * @Route("/profile/{id}", name="entreprise_show", methods={"GET"})
    */
    public function show(Entreprise $entreprise): Response
    {
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }

    /**
    * @Route("/{id}/edit", name="entreprise_edit", methods={"GET","POST"})
    */
    public function edit(Request $request, Entreprise $entreprise): Response
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['photoUrl']->getData(); 
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            try{
                $file->move($this->getParameter('image_directory'),$fileName);
            }catch (FileException $e){

            }

            $entreprise->setPhotoUrl($fileName);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le compte a été modifié avec succes');

            return $this->redirectToRoute('entreprise_show',array('id' => $entreprise->getId()));
        }

        return $this->render('entreprise/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form->createView(),
        ]);
    }

    /**
    * @Route("/{id}", name="entreprise_delete", methods={"DELETE"})
    */
    public function delete(Request $request, Entreprise $entreprise): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entreprise->setDeleted(1);
            $entityManager->flush();
            return $this->redirectToRoute('app_logout');
        }
        return $this->redirectToRoute('entreprise_show',array('id' => $entreprise->getId()));
    }
}
