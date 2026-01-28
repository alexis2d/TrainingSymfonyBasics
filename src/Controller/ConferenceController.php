<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/conference')]
class ConferenceController extends AbstractController
{
    #[Route('/{name}/{start}/{end}', name: 'app_conference_new')]
    public function add(string $name, string $start, string $end, EntityManagerInterface $entityManager): Response
    {
        $conference = (new Conference())
            ->setName($name)
            ->setDescription('Some generic description')
            ->setAccessible(true)
            ->setStartAt(new \DateTimeImmutable($start))
            ->setEndAt(new \DateTimeImmutable($end));

        $entityManager->persist($conference);
        $entityManager->flush();

        return new Response('Conference created');
    }

    #[Route('/', name: 'app_conference_list')]
    public function list(ConferenceRepository $conferenceRepository): Response
    {
        $conferences = $conferenceRepository->findAll();
        return $this->render('conference/list.html.twig', ['conferences' => $conferences]);
    }

    #[Route('/{id}', name: 'app_conference_show')]
    public function show(Conference $conference): Response
    {
        return $this->render('conference/show.html.twig', ['conference' => $conference]);
    }

    #[Route('/{start}/{end}', name: 'app_conference_list_by_dates')]
    public function listByDates(string $start, string $end, ConferenceRepository $conferenceRepository): Response
    {
        $startAt = $start ? new \DateTimeImmutable($start) : null;
        $endAt = $end ? new \DateTimeImmutable($end) : null;
        $conferenceRepository->findByDates($startAt, $endAt)->toArray();
        return new Response('Test');
    }

}
