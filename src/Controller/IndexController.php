<?php
namespace App\Controller;

use App\Dto\SelectedAnswerDto;
use App\Form\TestingForm;
use App\Manager\ParticipantManager;
use App\Manager\QuestionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private const PARTICIPANT_SESSION_KEY = 'participant_session_key';

    #[Route('/', name: 'homepage')]
    public function index(
        Request $request,
        ParticipantManager $participantManager,
        QuestionManager $questionManager
    ): Response {
        $session = $request->getSession();
        $participantUid = $this->getParticipantUid($session);
        $participant = $participantManager->getParticipant($participantUid);

        $question = $questionManager->getRandomQuestion($participant);
        if (!$question) {
            $session->remove(self::PARTICIPANT_SESSION_KEY);
            $result = $participantManager->getResult($participant);

            return $this->render('result.html.twig', [
                'result' => $result
            ]);
        }

        $form = $this->createForm(TestingForm::class, new SelectedAnswerDto(), ['question' => $question]);

        return $this->render('index.html.twig', [
            'form' => $form,
            'question' => $question
        ]);
    }

    #[Route('/process/{questionId}', name: 'process')]
    public function process(
        int $questionId,
        Request $request,
        ParticipantManager $participantManager,
        QuestionManager $questionManager
    ): Response {
        $session = $request->getSession();
        $participantUid = $this->getParticipantUid($session);
        $participant = $participantManager->getParticipant($participantUid);
        $question = $questionManager->findQuestion($questionId);

        $form = $this->createForm(TestingForm::class, new SelectedAnswerDto(), ['question' => $question]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $participantManager->processParticipantAnswer($participant, $form->getData());
            return $this->redirectToRoute('homepage');
        }

      throw new BadRequestException("Form is not valid");
    }

    private function getParticipantUid(Session $session): string
    {
        $uid = $session->get(self::PARTICIPANT_SESSION_KEY);
        if (!$uid) {
            $uid = uniqid(); // Не гарантирует уникальность, но тут подойдет
            $session->set(self::PARTICIPANT_SESSION_KEY, $uid);
        }
        return $uid;
    }
}