<?php

namespace App\Controller;

use App\Entity\Personality;
use App\Form\PersonalityType;
use App\Repository\PersonalityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/moder")
 */
class PersonalityController extends AbstractController
{
    /**
     * @Route("/", name="personality_index", methods="GET")
     */
    public function index(PersonalityRepository $personalityRepository): Response
    {
        return $this->render('personality/index.html.twig', ['personalities' => $personalityRepository->findAll()]);
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

//            $file = $personality->getSlidename();
//
//            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
//
//            // Move the file to the directory where brochures are stored
//            try {
//                $file->move(
//                    $this->getParameter('../img'),
//                    $fileName
//                );
//            } catch (FileException $e) {
//                // ... handle exception if something happens during file upload
//            }
//
//            // updates the 'brochure' property to store the PDF file name
//            // instead of its contents
//            $personality->setSlidename($fileName);

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
        $form = $this->createForm(PersonalityType::class, $personality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
}
