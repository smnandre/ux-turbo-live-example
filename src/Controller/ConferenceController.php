<?php

namespace App\Controller;

use App\Form\ConferenceType;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\Helper\TurboStream;
use Symfony\UX\Turbo\TurboBundle;
use Symfony\UX\Turbo\TurboStreamResponse;

final class ConferenceController extends AbstractController
{
    public function __construct(
        private readonly ConferenceRepository $conferenceRepository,
    ) {
    }

    #[Route('/conference', name: 'app_conference_search')]
    public function index(): Response
    {
        $conferences  = $this->conferenceRepository->findAll();

        return $this->render('conference/index.html.twig', [
            'conferences' => $conferences,
        ]);
    }

    #[Route('/conference/{id}', name: 'app_conference_show')]
    public function __invoke(int $id): Response
    {
        $conference = $this->conferenceRepository->get($id);

        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
        ]);
    }

    #[Route('/conference/{id}/update', name: 'app_conference_update')]
    public function update(Request $request, int $id): Response
    {
        $conference = $this->conferenceRepository->get($id);

        $form = $this->createForm(ConferenceType::class, $conference, [
            'action' => $this->generateUrl('app_conference_update', ['id' => $id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // SAVE
            // $this->conferenceRepository->save($conference);

            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                return (new TurboStreamResponse())
                    ->remove('#conference_card_form_'.$id)
                    ->replace(
                        '#conference_card_show'.$id,
                        $this->renderBlockView('conference/_card.html.twig', 'card_show', [
                            'conference' => $conference,
                        ])
                    );
            }
            
            return $this->redirectToRoute('app_conference_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('conference/update.html.twig', [
            'form' => $form->createView(),
            'conference' => $conference,
        ]);
    }
}
