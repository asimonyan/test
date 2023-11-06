<?php
namespace App\Command;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:init-data')]
class InitDataCommand extends Command
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $questions = $this->entityManager->getRepository(Question::class)->findBy([], [],1);
        if (!$questions) {
            return Command::SUCCESS;
        }

        $questions = [
            '1 + 1 =' => ['3' => false, '2' => true, '0' => false],
            '2 + 2 =' => ['4' => true, '3 + 1' => true, '10' => true],
            '3 + 3 =' => ['1 + 5' => true, '1' => false, '6' => true, '2 + 4' => true],
            '4 + 4 =' => ['8' => true, '4' => false, '0' => false, '0 + 8' => true],
            '5 + 5 =' => ['6' => false, '18' => false, '10' => true, '9' => false, '0' => true],
            '6 + 6 =' => ['3' => false, '9' => false, '0' => false, '12' => true, '5 + 7' => true],
            '7 + 7 =' => ['5' => false, '14' => true],
            '8 + 8 =' => ['16' => true, '12' => false, '9' => false, '5' => false],
            '9 + 9 =' => ['18' => true, '9' => false, '17 + 1' => true, '2 + 16' => true],
            '10 + 10 =' => ['0' => false, '2' => false, '8' => false, '20' => true],
        ];

        foreach ($questions as $title => $answers) {
            $questionObj = new Question();
            $questionObj->setTitle($title);
            $this->entityManager->persist($questionObj);

            foreach ($answers as $answer => $isCorrect) {
                $answerObj = new Answer();
                $answerObj->setTitle($answer);
                $answerObj->setIsCorrect($isCorrect);
                $answerObj->setQuestion($questionObj);
                $this->entityManager->persist($answerObj);
            }
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}

