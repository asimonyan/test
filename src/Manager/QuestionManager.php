<?php
namespace App\Manager;

use App\Entity\Participant;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuestionManager
{
    private EntityManagerInterface $entityManager;
    private ParticipantManager $participantManager;

    public function __construct(EntityManagerInterface $entityManager, ParticipantManager $participantManager)
    {
        $this->entityManager = $entityManager;
        $this->participantManager = $participantManager;
    }

    public function getRandomQuestion(Participant $participant): ?Question
    {
        $participantAnsweredQuestionDql = $this->participantManager->getParticipantAnsweredQuestionDql($participant);

        $questionDql = $this->entityManager
            ->getRepository(Question::class)
            ->createQueryBuilder('q' )
            ->addSelect('RAND() as HIDDEN rand')
            ->orderBy("rand");

        $questionDql->andWhere(
            $questionDql->expr()->notIn("q.id", "{$participantAnsweredQuestionDql}")
        );

        return $questionDql
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findQuestion(string $id): Question
    {
        $question = $this->entityManager->getRepository(Question::class)->find($id);
        if (!$question) {
            throw new NotFoundHttpException("Question (#$id) not found");
        }
        return $question;
    }
}



