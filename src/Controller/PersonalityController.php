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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/moder")
 * @IsGranted("ROLE_MODERATOR")
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
        $personality->setAuthor($this->getUser());
        $form = $this->createForm(PersonalityType::class, $personality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $personality->getSlide();
            $fileNameSlide = $this->moveFileOrException($file);
            $personality->setSlide($fileNameSlide);

          //  $fileAvatar = $personality->getAvatar();
            $fileNameAvatar = $this->moveFileOrException($personality->getAvatar());
            $personality->setAvatar($fileNameAvatar);

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
        $personality->setAvatar(
            new File($this->getParameter('avatars_directory').'/'.$personality->getAvatar())
        );

        $personality->setSlide(
            new File($this->getParameter('slides_directory').'/'.$personality->getSlide())
        );

        $form = $this->createForm(PersonalityType::class, $personality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $personality->getSlide();
            $fileName = $this->moveFileOrException($file, 'slides_directory');
            $personality->setSlide($fileName);

            $fileNameAvatar = $this->moveFileOrException($personality->getAvatar(), 'avatars_directory');
            $personality->setAvatar($fileNameAvatar);

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

    private function moveFileOrException($file, $path)
    {
        $fileName = uniqid().'.'.$file->guessExtension();

        try {
            $file->move(
                $this->getParameter($path),
                $fileName
            );
            return $fileName;

        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
    }
}
