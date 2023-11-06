<?php
namespace App\Dto;

use App\Entity\Question;
use Doctrine\Common\Collections\ArrayCollection;

class SelectedAnswerDto
{
    public Question $question;
    public ArrayCollection $selectedAnswers;
}

