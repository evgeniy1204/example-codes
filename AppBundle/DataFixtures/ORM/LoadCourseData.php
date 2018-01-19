<?php

namespace BetaOmega\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use BetaOmega\AppBundle\Entity\Course;

class LoadCourseData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    const REFERENCE_STRING = 'beta-omega.course.';

    const COURSE_ARRAY = [
    		0 => 'Cleam',
    		1 => 'Clef',
    		2 => 'Cleacc',
    		3 => 'Cles',
    		4 => 'Biem',
    		5 => 'Clmg',
    		6 => 'Bief',
    		7 => 'Big',
    		8 => 'Bemacs',
    		9 => 'M',
    		10 => 'Im',
    		11 => 'mm',
    		12 => 'Afc',
    		13 => 'Gio',
    		14 => 'Clefin/Finance',
    		15 => 'Cleli',
    		16 => 'Acme',
    		17 => 'Des/Ess',
    		18 => 'Emit'
    	];

    const COURSE_CLASSES_ARRAY = [
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

    public function load(ObjectManager $manager)
    {
    	

    	foreach (self::COURSE_CLASSES_ARRAY as $key => $value) {
	        $course = new Course();
	        $course->setName($key);

	        $manager->persist($course);

	        $this->addReference(self::REFERENCE_STRING . $key, $course);
    	}
    

    	$manager->flush();
    	
    }
}