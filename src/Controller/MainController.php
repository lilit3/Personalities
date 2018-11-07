<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Personality;
use App\Form\AnswerType;
use App\Form\TestType;
use App\Entity\Comment;
use App\Repository\PersonalityRepository;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/types", name="personality_types")
     */
    public function personalityTypes(RoleRepository $roleRepository, PersonalityRepository $personalityRepository)
    {
        $roles = $roleRepository->findAll();
        //$personalities = $personalityRepository->findAll();
        $personalities = $personalityRepository->findBy(['publish' => 1]);


        return $this->render('main/personality_types.html.twig', [
            'roles' => $roles,
            'personalities' => $personalities,
        ]);
    }

    /**
     * @Route("/personality/{id}", name="personality_one", requirements={"id"="\d+"}))
     */
    public function personality($id, PersonalityRepository $personalityRepository)
    {
        $personality = $personalityRepository->find($id);
        return $this->render('main/personality.html.twig', compact('personality'));
    }

    /**
     * @Route("/contact-us", name="contact")
     */
    public function contact()
    {
        return $this->render('main/contact_us.html.twig');
    }

    /**
     * @Route("/{id}/new-comment", name="personality_create_comment", methods="POST")
     */
    public function newComment(Personality $personality, Request $request, EntityManagerInterface $entityManager)
    {
        $comment = ( new Comment())
                 ->setPersonality($personality)
                 ->setAuthor($this->getUser())
                 ->setText( $request->request->get('text'));

        $entityManager->persist($comment);
        $entityManager->flush();

       // $this->addFlash('add-comment-success', 'Your comment added success!');

        $id = $request->request->get('personalityId');
        return $this->redirectToRoute('personality_one', ['id' => $id]);
    }

//    /**
//     * @Route("/test", name="show_test")
//     */
//    public function showTest(Request $request): Response
//    {
//        //$personality = new Personality();
//
//        $form = $this->createForm(AnswerType::class, $personality);
//
//        $form->handleRequest($request);
//        return $this->render('main/show_test.html.twig', [
//            'personality' => $personality,
//            'form' => $form->createView(),
//        ]);
//    }
}
