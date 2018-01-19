<?php

namespace BetaOmega\AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextareaType, ChoiceType, HiddenType};
use Doctrine\Common\Persistence\ObjectManager;

use BetaOmega\AppBundle\Form\DataTransformer\IdToEntityTransformer;
use BetaOmega\AppBundle\Entity\Complaints;

class ComplaintsType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    protected $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['data']->setUser($options['user']);


        $builder
            ->add('complaint', ChoiceType::class, [
                'choices' => [
                    'È spam' => 'È spam',
                    'Non è appropriato' => 'Non è appropriato'
                ],
                'label' => false
                ])
            ->add('text', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Or write your message']
                ]);

        if ($options['type'] == 'question') {
            $question = $this->om->getRepository('SynopsesBundle:Question')->find($options['id']);
            $builder->add('question', HiddenType::class, [
                'data_class' => null,
                'attr' => ['class' => 'entity-complaint'],
                'data' => $question]);
            $builder->get('question')->addModelTransformer(new IdToEntityTransformer($this->om, 'SynopsesBundle:Question'));
        }

        if ($options['type'] == 'topic') {
            $topic = $this->om->getRepository('ForumBundle:Topic')->find($options['id']);
            $builder->add('topic', HiddenType::class, [
                'data_class' => null,
                'attr' => ['class' => 'entity-topic'],
                'data' => $topic]);
            $builder->get('topic')->addModelTransformer(new IdToEntityTransformer($this->om, 'ForumBundle:Topic'));
        }

        if ($options['type'] == 'review') {
            $review = $this->om->getRepository('SynopsesBundle:Review')->find($options['id']);
            $builder->add('review', HiddenType::class, [
                'data_class' => null,
                'attr' => ['class' => 'entity-review'],
                'data' => $review]);
            $builder->get('review')->addModelTransformer(new IdToEntityTransformer($this->om, 'SynopsesBundle:Review'));
        }
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BetaOmega\AppBundle\Entity\Complaints',
            'user' => null,
            'question' => null,
            'isTopic' => null,
            'review' => null,
            'id' => null,
            'type' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'betaomega_appbundle_complaints';
    }


}
