<?php

namespace App\Controller;

use App\Entity\Conference;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConferenceController extends AbstractController
{
    #[Route('/conference/{name}/{start}/{end}', name: 'app_conference_new')]
    public function newConference(string $name, string $start, string $end, EntityManagerInterface $entityManager): Response
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
}
