<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Form\Usuarios1Type;
use App\Repository\UsuariosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
#[Route('/usuarios')]
class UsuariosController extends AbstractController
{

   
    #[Route('/', name: 'app_usuarios_index', methods: ['GET'])]
    public function index(UsuariosRepository $usuariosRepository): Response
    {
        return $this->render('usuarios/index.html.twig', [
            'usuarios' => $usuariosRepository->findBy(['estado' => true])
        ]);
    }


    #[Route('/new', name: 'app_usuarios_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $usuario = new Usuarios();
        $form = $this->createForm(Usuarios1Type::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            $dni = $usuario->getDni();
            if (!$this->isValidDniFormat($dni)) {
                $this->addFlash('error', 'El formato del DNI no es válido.');
                return $this->redirectToRoute('app_usuarios_new');
            }

           
            $entityManager->persist($usuario);
            $entityManager->flush();

            return $this->redirectToRoute('app_usuarios_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('usuarios/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

    private function isValidDniFormat($dni): bool
    {
        return preg_match('/^\d{8}[A-Za-z]$/', $dni) === 1;
    }

    #[Route('/{id}', name: 'app_usuarios_show', methods: ['GET'])]
    public function show(Usuarios $usuario): Response
    {
        return $this->render('usuarios/show.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_usuarios_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Usuarios $usuario, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Usuarios1Type::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_usuarios_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('usuarios/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
        ]);
    }
     #[Route('/{id}', name: 'app_usuarios_delete', methods: ['POST'])]
     public function delete(Request $request, Usuarios $usuario, EntityManagerInterface $entityManager): Response
    {
         if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->getPayload()->get('_token'))) {
             $entityManager->remove($usuario);
             $entityManager->flush();
        }

         return $this->redirectToRoute('app_usuarios_index', [], Response::HTTP_SEE_OTHER);
     }


    
   
}

