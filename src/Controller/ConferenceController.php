<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/conference')]
class ConferenceController extends AbstractController
{
    #[Route('/new', name: 'app_conference_new', methods: ['GET', 'POST'])]
    public function newConference(Request $request, EntityManagerInterface $manager): Response
    {
        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($conference);
            $manager->flush();

            return $this->redirectToRoute('app_conference_show', ['id' => $conference->getId()]);
        }

        return $this->render('conference/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/', name: 'app_conference_list')]
    public function list(ConferenceRepository $conferenceRepository, Request $request): Response
    {
        $startAtString = $request->query->getString('start');
        $startAt = $startAtString ? new \DateTimeImmutable($startAtString) : null;
        $endAtString = $request->query->getString('end');
        $endAt = $endAtString ? new \DateTimeImmutable($endAtString) : null;
        if ($startAt === null && $endAt === null) {
            $conferences = $conferenceRepository->findAll();
        } else {
            $conferences = $conferenceRepository->findByDates($startAt, $endAt);
        }

        if (is_array($conferences) === false) {
            $conferences = [];
        }

        return $this->render('conference/list.html.twig', ['conferences' => $conferences]);
    }

    #[Route('/{id}', name: 'app_conference_show')]
    public function show(Conference $conference): Response
    {
        return $this->render('conference/show.html.twig', ['conference' => $conference]);
    }

}
