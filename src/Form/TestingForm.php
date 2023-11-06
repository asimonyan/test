<?php
namespace App\Form;

use App\Dto\SelectedAnswerDto;
use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestingForm extends AbstractType
{
    private const ANSWER_OPTIONS_FIELD = 'answerOptions';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Question $question  */
        $question = $options['question'];

        $builder
            ->add(self::ANSWER_OPTIONS_FIELD, EntityType::class, [
                'class' => Answer::class,
                'label' => $question->getTitle(),
                'query_builder' => function (EntityRepository $er) use ($question) : QueryBuilder {
                    return $er->createQueryBuilder('a')
                        ->where('a.question = :question')
                        ->setParameter('question', $question->getId());
                },
                'multiple' => true,
                'required' => true,
                'expanded' => true,
                'mapped' => false
            ])
            ->add('save', SubmitType::class);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($question): void {
            $answers = $event->getForm()->get(self::ANSWER_OPTIONS_FIELD)->getData();
            if ($answers->count() == 0) {
                throw new BadRequestException("Answer is required field");
            }
            /** @var SelectedAnswerDto $data */
            $data = $event->getData();
            $data->selectedAnswers = $event->getForm()->get(self::ANSWER_OPTIONS_FIELD)->getData();
            $data->question = $question;
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'question' => false,
            'data_class' => SelectedAnswerDto::class,
        ]);
        $resolver->setAllowedTypes('question', Question::class);
    }
}
