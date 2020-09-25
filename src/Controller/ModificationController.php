<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\ModifierPasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\ResetType;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/users")
 * @IsGranted({"ROLE_LAUREAT", "ROLE_ENTREPRISE", "ROLE_SECRETAIRE", "ROLE_ETABLISSEMENT", "ROLE_DIRECTEUR"})
 */
class ModificationController extends AbstractController
{

    /**
     * @Route("/password-modification", name="app_password_modification")
     */
    public function editAction(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(ModifierPasswordType::class, $user);
        // dd($user);
        // die();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request);
            $oldPassword = $request->request->get('modifier_password')['oldPassword'];
            $newPassword = $request->request->get('modifier_password')['plainPassword']['first'];
            $confirmPassword = $request->request->get('modifier_password')['plainPassword']['second'];
            if($newPassword !== $confirmPassword) {
              $this->addFlash('error', 'the two passwords id not mutch pls enter correct');
              return $this->redirect($request->getUri());
            }
            if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {
              $newEncodedPassword = $passwordEncoder->encodePassword($user, $newPassword);
              $user->setPassword($newEncodedPassword);
              $em->persist($user);
              $em->flush();
              return $this->redirectToRoute('app_logout');
            } else {
              $this->addFlash('error', 'old password is wrong !');
              return $this->redirect($request->getUri());
            }
        }
        return $this->render('security/password-modification.html.twig', array(
            'form' => $form->createView(),
        ));
    }


}
