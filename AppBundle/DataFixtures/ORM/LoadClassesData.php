<?php

namespace BetaOmega\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use BetaOmega\AppBundle\DataFixtures\ORM\LoadCourseData;
use Doctrine\Common\DataFixtures\AbstractFixture;
use BetaOmega\AppBundle\Entity\Classes;

class LoadClassesData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, DependentFixtureInterface
{
    use ContainerAwareTrait;

    const REFERENCE_STRING = 'beta-omega.classes.';

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return [
            LoadCourseData::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
    	$coursesClassesArray = [
    		'Cleam' => [1, 2, 3, 4, 5, 6, 7, 8],
    		'Clef' => [9, 10],
    		'Cleacc' => [11, 12],
    		'Cles' => [13],
    		'Biem' => [14, 15, 16, 17, 18],
    		'Clmg' =>  [19, 20],
    		'Bief' => [21, 22],
    		'Big' => [23],
    		'Bemacs' => [24],
    		'M' => [1, 2, 3, 4, 5],
    		'Im' => [6, 7],
    		'mm' => [8, 9, 10],
    		'Afc' => [11, 12],
    		'Gio' => [13],
    		'Clefin/Finance' => [14, 15, 16],
    		'Cleli' => [17],
    		'Acme' => [18],
    		'Des/Ess' => [19, 20],
    		'Emit' => [21]
    	];

    	foreach ($coursesClassesArray as $courses => $courseClasses) {
    		/** @var $course Course */
	        $course = $this->getReference(LoadCourseData::REFERENCE_STRING . $courses);

    		foreach ($courseClasses as $class) {
    			$classes = new Classes();
		        $classes->setName($class);
		        $classes->setCourse($course);
		        $manager->persist($classes);

		        $this->addReference(self::REFERENCE_STRING . $class . $courses, $classes);
    		}     
    	}

    	$manager->flush();
    }
}