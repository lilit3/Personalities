<?php

namespace App\Controller;

use App\Entity\Personality;
use App\Form\PersonalityType;
use App\Repository\PersonalityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @Route("/moder")
 */
class PersonalityController extends AbstractController
{
    /**
     * @Route("/", defaults={"id": false}, name="personality_index", methods="GET")
     * @Route("/role/{id}", name="personality_index_role", methods="GET")
     */
    public function index(PersonalityRepository $personalityRepository, $id = false): Response
    {
        if ($id != false) {
            $personalities = $personalityRepository->findBy(['role' => $id]);
        } else {
            $personalities = $personalityRepository->findAll();
        }

        return $this->render('personality/index.html.twig', compact('personalities'));
    }

    /**
     * @Route("/new", name="personality_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $personality = new Personality();
        $form = $this->createForm(PersonalityType::class, $personality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            #код повторяется в update
            $file = $personality->getSlide();
            $fileName = $this->moveFileOrException($file);
            $personality->setSlide($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($personality);
            $em->flush();

            return $this->redirectToRoute('personality_index');
        }

        return $this->render('personality/new.html.twig', [
            'personality' => $personality,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="personality_show", methods="GET")
     */
    public function show(Personality $personality): Response
    {
        return $this->render('personality/show.html.twig', ['personality' => $personality]);
    }

    /**
     * @Route("/{id}/edit", name="personality_edit", methods="GET|POST")
     */
    public function edit(Request $request, Personality $personality): Response
    {
        $personality->setSlide(
            new File($this->getParameter('slides_directory').'/'.$personality->getSlide())
        );
        $form = $this->createForm(PersonalityType::class, $personality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            #код повторяется в new
            #нет проверки, а надо  ли нам обновлять файл или не  надо нам обновлять файл...
            $file = $personality->getSlide();
            $fileName = $this->moveFileOrException($file);
            $personality->setSlide($fileName);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('personality_edit', ['id' => $personality->getId()]);
        }

        return $this->render('personality/edit.html.twig', [
            'personality' => $personality,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="personality_delete", methods="DELETE")
     */
    public function delete(Request $request, Personality $personality): Response
    {
        if ($this->isCsrfTokenValid('delete'.$personality->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($personality);
            $em->flush();
        }

        return $this->redirectToRoute('personality_index');
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    private function moveFileOrException($file)
    {
        $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
        
        try {
            $file->move(
                $this->getParameter('slides_directory'),
                $fileName
            );
            return $fileName;

        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
    }
}
