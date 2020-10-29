<?php

namespace App\Controller;

use App\Entity\Tarea;
use App\Repository\TareaRepository;
use App\Service\TareaManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TareaController extends AbstractController
{
    /**
     * @Route("/", name="app_listado_tarea")
     */
    public function listado(TareaRepository $tareasRepository): Response
    {
        $tareas = $tareasRepository->findAll();
        return $this->render('tarea/listado.html.twig', [
            'tareas' => $tareas,
        ]);
    }

    /**
     * @Route("/crear-tarea", name="app_crear_tarea")
     */
    public function crear(Request $request, EntityManagerInterface $em): Response
    {

        $descripcion = $request->request->get('descripcion', null);
        $tarea = new Tarea();
        if (null !== $descripcion) {
            if (!empty($descripcion)) {
                $em = $this->getDoctrine()->getManager();
                $tarea->setDescripcion($descripcion);
                $em->persist($tarea);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Tarea creada correctamente!'
                );
                return $this->redirectToRoute('app_listado_tarea');
            } else {
                $this->addFlash(
                    'warning',
                    'El campo "Descripción es obligatorio"'
                );
            }
        }
        return $this->render('tarea/crear.html.twig', [
            "tarea" => $tarea,
        ]);
    }

    /**
     * @Route(
     *      "/editar-tarea/{id}",
     *      name="app_editar_tarea",
     *      requirements={"id"="\d+"}
     * )
     */
    public function editar(int $id, TareaRepository $tareaRepository, Request $request, EntityManagerInterface $em): Response
    {
        $tarea = $tareaRepository->find($id);
        //$tarea = $tareaRepository->findOneById($id);
        if (null === $tarea) {
            throw  $this->createNotFoundException();
        }
        $descripcion = $request->request->get('descripcion', null);
        if (null !== $descripcion) {
            if (!empty($descripcion)) {
                $em = $this->getDoctrine()->getManager();
                $tarea->setDescripcion($descripcion);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Tarea editada correctamente!'
                );
                return $this->redirectToRoute('app_listado_tarea');
            } else {
                $this->addFlash(
                    'warning',
                    'El campo "Descripción" es obligatorio'
                );
            }
        }
        return $this->render('tarea/editar.html.twig', [
            "tarea" => $tarea,
        ]);
    }

    /**
     * Con paramsConvert
     * @Route(
     *      "/editar-tarea-convert/{id}",
     *      name="app_editar_tarea_convert",
     *      requirements={"id"="\d+"}
     * )
     */
    public function editarParamsConvert(Tarea $tarea, Request $request): Response
    {
        $descripcion = $request->request->get('descripcion', null);
        if (null !== $descripcion) {
            if (!empty($descripcion)) {
                $em = $this->getDoctrine()->getManager();
                $tarea->setDescripcion($descripcion);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Tarea editada correctamente!'
                );
                return $this->redirectToRoute('app_listado_tarea');
            } else {
                $this->addFlash(
                    'warning',
                    'El campo "Descripción" es obligatorio'
                );
            }
        }
        return $this->render('tarea/editar.html.twig', [
            "tarea" => $tarea,
        ]);
    }

    /**
     * Con paramsConvert
     * @Route(
     *      "/eliminar-tarea/{id}",
     *      name="app_eliminar_tarea",
     *      requirements={"id"="\d+"}
     * )
     */
    public function eliminar(Tarea $tarea): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($tarea);
        $em->flush();
        $this->addFlash(
            'success',
            'Tarea eliminada correctamente!'
        );

        return $this->redirectToRoute('app_listado_tarea');
    }
    // ------------------- servicios ----------------------------------

    /**
     * @Route("/crear-tarea-servicio", name="app_crear_tarea_servicio")
     */
    public function crearServicio(TareaManager $tareaManager, Request $request): Response
    {
        $descripcion = $request->request->get('descripcion', null);
        $tarea = new Tarea();
        if (null !== $descripcion) {
            $tarea->setDescripcion($descripcion);
            $errores = $tareaManager->validar($tarea);

            if (empty($errores)) {
                $tareaManager->crear($tarea);
                $this->addFlash(
                    'success',
                    'Tarea creada correctamente!'
                );
                return $this->redirectToRoute('app_listado_tarea');
            } else {
                foreach ($errores as $error) {
                    $this->addFlash(
                        'warning',
                        $error->getMessage()
                    );
                }
            }
        }
        return $this->render('tarea/crear.html.twig', [
            "tarea" => $tarea,
        ]);
    }

    /**
     * Con paramsConvert
     * @Route(
     *      "/editar-tarea-servicio/{id}",
     *      name="app_editar_tarea_servicio",
     *      requirements={"id"="\d+"}
     * )
     */
    public function editarParamsConvertServicio(TareaManager $tareaManager, Tarea $tarea, Request $request): Response
    {
        $descripcion = $request->request->get('descripcion', null);
        if (null !== $descripcion) {
            $tarea->setDescripcion($descripcion);
            $errores = $tareaManager->validar($tarea);

            if (0 === count($errores)) {
                $tareaManager->crear($tarea);
                $this->addFlash(
                    'success',
                    'Tarea editada correctamente!'
                );
                return $this->redirectToRoute('app_listado_tarea');
            } else {
                foreach ($errores as $error) {
                    $this->addFlash(
                        'warning',
                        $error->getMessage()
                    );
                }
            }
        }
        return $this->render('tarea/editar.html.twig', [
            "tarea" => $tarea,
        ]);
    }

    /**
     * Con paramsConvert
     * @Route(
     *      "/eliminar-tarea-servicio/{id}",
     *      name="app_eliminar_tarea_servicio",
     *      requirements={"id"="\d+"}
     * )
     */
    public function eliminarServicio(Tarea $tarea, TareaManager $tareaManager): Response
    {
        $tareaManager->eliminar($tarea);
        $this->addFlash(
            'success',
            'Tarea eliminada correctamente!'
        );

        return $this->redirectToRoute('app_listado_tarea');
    }
}
