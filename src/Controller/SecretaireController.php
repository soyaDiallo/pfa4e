<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\Secretaire;
use App\Form\SecretaireType;
use App\Repository\DemandeRepository;
use App\Repository\SecretaireRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
/**
 * @Route("/secretaire")
 * @IsGranted({"ROLE_SECRETAIRE"})
 */
class SecretaireController extends AbstractController {

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
     * @Route("/profildemande/{id}", name="secretaire_demande", methods={"GET","POST"})
     */
    public function profildemande(SecretaireRepository $secretaireRepository,DemandeRepository $demandeRepository,Secretaire $secretaire,$id):Response
    {
        $IdEtab = $secretaireRepository->getIdEtab(intval($id));

        return $this->render('secretaire/demande.html.twig',[
            'secretaire' => $secretaire,
            'demandes' => $demandeRepository->getSecDemandes(intval($IdEtab))
        ]);
    }

    /**
     * @Route("/valide/demande/{id}/{sec}", name="secretaire_valide", methods={"GET","POST"})
     */
    public function valideeDemande(DemandeRepository $demandeRepository,$id,$sec): Response
    {
        $date = new \DateTime('now');

        $demandeRepository->validateDemandeBySec($id,$date,1,$sec);

        return $this->redirectToRoute('secretaire_demande', array(
            'id' => $sec));
    }

    /**
     * @Route("/annuler/demande/{id}/{sec}", name="secretaire_annuler", methods={"GET","POST"})
     */
    public function annulerDemande(DemandeRepository $demandeRepository,$id,$sec): Response
    {
        $date = new \DateTime('now');

        $demandeRepository->annulerDemandeBySec($id,0,$date,$sec);

        return $this->redirectToRoute('secretaire_demande', array(
            'id' => $sec));

    }

}
