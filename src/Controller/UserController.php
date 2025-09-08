<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $UserRepo): Response
    {
        $datas = $UserRepo->findAll();
        return $this->render('registration/index.html.twig', [
            'datas' => $datas,
        ]);
    }



    #[Route('/user/delete/{id}', name: 'delete_user')]
    public function delete($id, EntityManagerInterface $entityManager): Response
    {
        $crud = $entityManager->getRepository(User::class)->find($id);
        $entityManager->remove($crud);
        $entityManager->flush();

        return $this->redirectToRoute('app_user');
    }
}
