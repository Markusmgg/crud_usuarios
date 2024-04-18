<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Form\Usuarios1Type;
use App\Repository\UsuariosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
#[Route('/usuarios')]
class UsuariosController extends AbstractController
{

    //modificar index con filtro para no mostrar usuarios con estado
    #[Route('/', name: 'app_usuarios_index', methods: ['GET'])]
    public function index(UsuariosRepository $usuariosRepository): Response
    {
        return $this->render('usuarios/index.html.twig', [
            'usuarios' => $usuariosRepository->findBy(['estado' => true])
        ]);
    }

    #[Route('/datatable', name: 'app_usuarios_datatable', methods: ['GET'])]
    public function indexAjax(UsuariosRepository $usuariosRepository): JsonResponse
    {
        $usuarios = $usuariosRepository->findBy(['estado' => true]);
        $data = [];

        foreach ($usuarios as $usuario) {
            $data[] = [
                'id' => $usuario->getId(),
                'dni' => $usuario->getDni(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellidos(),
                'ciudad' => $usuario->getCiudad(),
                'direccion' => $usuario->getDireccion()
            ];
        }

        return $this->json(['data' => $data]);
    }

    // #[Route('/', name: 'app_usuarios_index', methods: ['GET'])]
    // public function index(UsuariosRepository $usuariosRepository): Response
    // {
    //     return $this->render('usuarios/index.html.twig', [
    //         'usuarios' => $usuariosRepository->findAll(),
    //     ]);
    // }

    #[Route('/new', name: 'app_usuarios_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usuario = new Usuarios();
        $form = $this->createForm(Usuarios1Type::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($usuario);
            $entityManager->flush();

            return $this->redirectToRoute('app_usuarios_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('usuarios/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
        ]);
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

    // función delete modificada para no borrar, si no cambiar el estado
    #[Route('/{id}', name: 'app_usuarios_delete', methods: ['POST'])]
    public function delete(Request $request, Usuarios $usuario, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->getPayload()->get('_token'))) {
            $usuario->setEstado(false);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_usuarios_index', [], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/{id}', name: 'app_usuarios_delete', methods: ['POST'])]
    // public function delete(Request $request, Usuarios $usuario, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->getPayload()->get('_token'))) {
    //         $entityManager->remove($usuario);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_usuarios_index', [], Response::HTTP_SEE_OTHER);
    // }
    #[Route('/datatable', name: 'app_usuarios_datatable', methods: ['POST'])]
    public function dataTable(UsuariosRepository $usuariosRepository): JsonResponse
    {
        $usuarios = $usuariosRepository->findBy(['estado' => true]);
        $data = [];

        foreach ($usuarios as $usuario) {
            $data[] = [
                'id' => $usuario->getId(),
                'dni' => $usuario->getDni(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellidos(),
                'ciudad' => $usuario->getCiudad(),
                'direccion' => $usuario->getDireccion()
            ];
        }

        return $this->json(['data' => $data]);
    }
    #[Route('/{id}/show_ajax', name: 'app_usuarios_show_ajax', methods: ['GET'])]
public function showAjax(Usuarios $usuario): JsonResponse
{
    $data = [
        'id' => $usuario->getId(),
        'dni' => $usuario->getDni(),
        'nombre' => $usuario->getNombre(),
        'apellidos' => $usuario->getApellidos(),
        'ciudad' => $usuario->getCiudad(),
        'direccion' => $usuario->getDireccion()
    ];

    return $this->json($data);
}
#[Route('/{id}/edit_ajax', name: 'app_usuarios_edit_ajax', methods: ['GET'])]
public function editAjax(Request $request, Usuarios $usuario, EntityManagerInterface $entityManager): JsonResponse
{
    // Obtiene el formulario de edición del usuario
    $form = $this->createForm(Usuarios1Type::class, $usuario);

    // Renderiza el formulario como HTML y lo devuelve como respuesta AJAX
    $html = $this->renderView('usuarios/_form.html.twig', [
        'form' => $form->createView(),
    ]);

    return new JsonResponse(['html' => $html]);
}

#[Route('/crear_ajax', name: 'app_usuarios_crear_ajax', methods: ['POST'])]
public function crearAjax(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    // Obtener los datos del nuevo usuario de la solicitud POST
    $data = json_decode($request->getContent(), true);

    // Crear una nueva instancia de Usuarios con los datos recibidos
    $usuario = new Usuarios();
    $usuario->setDni($data['dni']);
    // Establecer otros campos del usuario según sea necesario

    // Persistir el nuevo usuario en la base de datos
    $entityManager->persist($usuario);
    $entityManager->flush();

    // Devolver una respuesta JSON con el ID del nuevo usuario
    return new JsonResponse(['id' => $usuario->getId()]);
}
}

