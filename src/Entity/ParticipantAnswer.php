<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'participant_answer')]
class ParticipantAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Participant::class, inversedBy: 'answers')]
    #[ORM\JoinColumn(name: 'participant_id', referencedColumnName: 'id')]
    private Participant $participant;
    #[ORM\ManyToOne(targetEntity: Question::class)]
    #[ORM\JoinColumn(name: 'question_id', referencedColumnName: 'id')]
    private Question $question;

    #[ORM\JoinTable(name: 'participant_selected_answer')]
    #[ORM\JoinColumn(name: 'participant_answer_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'answer', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Answer::class)]
    private Collection $selectedAnswers;

    #[ORM\Column(name: 'is_correct', type: 'boolean')]
    private bool $isCorrect;


    public function __construct()
    {
        $this->selectedAnswers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getParticipant(): Participant
    {
        return $this->participant;
    }

    public function setParticipant(Participant $participant): void
    {
        $this->participant = $participant;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): void
    {
        $this->question = $question;
    }

    public function getSelectedAnswers(): Collection
    {
        return $this->selectedAnswers;
    }

    public function setSelectedAnswers(Collection $selectedAnswers): void
    {
        $this->selectedAnswers = $selectedAnswers;
    }

    public function addSelectedAnswer(Answer $selectedAnswers): void
    {
        if (!$this->selectedAnswers->contains($selectedAnswers)) {
            $this->selectedAnswers->add($selectedAnswers);
        }
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): void
    {
        $this->isCorrect = $isCorrect;
    }
}
