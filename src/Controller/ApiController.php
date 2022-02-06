<?php

namespace App\Controller;

use App\Entity\Crud;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }

    /**
     * @Route("/api/post_api", name="post_api", methods={"POST"})
     */
    public function post_api(Request $request): Response
    {
        $crud =  new Crud();
        $parameter = json_decode($request->getContent(), true);
        $crud->setTitle($parameter['title']);
        $crud->setContent($parameter['content']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($crud);
        $em->flush();

        return $this->json('inserted successfully!');
    }

    /**
     * @Route("/api/update_api/{id}", name="update_api", methods={"PUT"})
     */
    public function update_api(Request $request, $id): Response
    {
        $data = $this->getDoctrine()->getRepository(Crud::class)->find($id);
        $crud =  new Crud();
        $parameter = json_decode($request->getContent(), true);
        $data->setTitle($parameter['title']);
        $data->setContent($parameter['content']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return $this->json('updated successfully!');
    }

    /**
     * @Route("/api/delete_api/{id}", name="delete_api", methods={"DELETE"})
     */
    public function delete_api($id): Response
    {
        $data = $this->getDoctrine()->getRepository(Crud::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();

        return $this->json('deleted successfully!');
    }

    /**
     * @Route("/api/fetch_all", name="fetch_all", methods={"GET"})
     */
    public function fetch_all(Request $request): Response
    {
        $data = $this->getDoctrine()->getRepository(Crud::class)->findAll();
        foreach($data as $d){
            $res[] = [
                'id' => $d->getId(),
                'title' => $d->getTitle(),
                'content' => $d->getContent()
            ];
        }

        return $this->json($res);
    }
}
