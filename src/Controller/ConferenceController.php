<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
