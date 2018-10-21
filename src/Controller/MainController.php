<?php

namespace App\Controller;

use App\Entity\Role;
use App\Repository\PersonalityRepository;
use App\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
    public function personality_types(RoleRepository $roleRepository, PersonalityRepository $personalityRepository)
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
     * @Route("/personality/{id}", name="personality_one")
     */
    public function personality($id, PersonalityRepository $personalityRepository)
    {
        $personality = $personalityRepository->find($id);
        return $this->render('main/personality.html.twig', compact('personality'));
    }

    /**
     * @Route("/contuct-us", name="contuct")
     */
    public function contuct()
    {
        return $this->render('main/contact-us.html.twig');
    }
}
