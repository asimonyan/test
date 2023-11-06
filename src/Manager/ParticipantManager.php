<?php
namespace App\Manager;

use App\Dto\SelectedAnswerDto;
use App\Entity\Participant;
use App\Entity\ParticipantAnswer;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class ParticipantManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getParticipant(string $sessionId): Participant
    {
        $repo = $this->entityManager->getRepository(Participant::class);
        $participant = $repo->findOneBy(['uid' => $sessionId]);
        if (!$participant) {
            $participant = new Participant();
            $participant->setUid($sessionId);
            $this->entityManager->persist($participant);
        }
        $this->entityManager->flush($participant);
        return $participant;
    }

    public function processParticipantAnswer(Participant $participant, SelectedAnswerDto $selectedAnswers): void
    {
        $participantAnswer = new ParticipantAnswer();
        $participantAnswer->setQuestion($selectedAnswers->question);
        $participantAnswer->setParticipant($participant);

        foreach ($selectedAnswers->selectedAnswers as $answer) {
            $participantAnswer->addSelectedAnswer($answer);
        }


        $participantAnswer->setIsCorrect($this->isCorrectAnswered($selectedAnswers));
        $this->entityManager->persist($participantAnswer);
        $this->entityManager->flush($participantAnswer);
    }

    public function getParticipantAnsweredQuestionDql(Participant $participant): string
    {
        $participantAnsweredQuestionDql = $this->entityManager
            ->getRepository(Participant::class)
            ->createQueryBuilder('p')
            ->select('paq.id')
            ->join('p.answers', 'pa')
            ->join('pa.question', 'paq');

        $participantAnsweredQuestionDql->andWhere(
            $participantAnsweredQuestionDql->expr()->eq("p.id", $participant->getId())
        );

        return $participantAnsweredQuestionDql->getDQL();
    }

    public function getResult(Participant $participant): array
    {
        return $this->entityManager->getRepository(ParticipantAnswer::class)->findBy(['participant' => $participant]);
    }

    private function isCorrectAnswered(SelectedAnswerDto $selectedAnswers): bool
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('isCorrect', true));
        $correctAnswers = $selectedAnswers->question->getAnswers()->matching($criteria)->toArray();

        $answers = $selectedAnswers->selectedAnswers->toArray();
        return count(array_diff($answers, $correctAnswers)) == 0;
    }
}



