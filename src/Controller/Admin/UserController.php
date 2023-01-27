<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserUpdateType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/User')]
class UserController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function editPerso(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            if ($user->getCandidat()) {
                return $this->redirectToRoute('admin_app_candidat_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('admin_app_consultant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/update.html.twig', [
            'user' => $user,
            'userForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $user->setEmail('user' . $user->getId() . '@deleted.fr');
            $user->setFirstname('firstname' . $user->getId());
            $user->setLastName('lastname' . $user->getId());
            $user->setIsActive(false);
            $userRepository->save($user, true);
        }
        if ($user->getCandidat()) {
            return $this->redirectToRoute('admin_app_candidat_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->redirectToRoute('admin_app_consultant_index', [], Response::HTTP_SEE_OTHER);
    }
}
