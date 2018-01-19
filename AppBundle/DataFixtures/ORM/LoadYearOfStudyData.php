<?php

namespace BetaOmega\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use BetaOmega\AppBundle\DataFixtures\ORM\LoadCourseData;
use BetaOmega\AppBundle\Entity\YearOfStudy;

class LoadYearOfStudyData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    const REFERENCE_STRING = 'beta-omega.year.';

    const COURSES_YEAR_ARRAY = [
            'Cleam' => [1, 2, 3],
            'Clef' => [1, 2, 3],
            'Cleacc' => [1, 2, 3],
            'Cles' => [1, 2, 3],
            'Biem' => [1, 2, 3],
            'Clmg' =>  [1, 2, 3],
            'Bief' => [1, 2, 3],
            'Big' => [1, 2, 3],
            'Bemacs' => [1, 2, 3],
            'M' => [1, 2],
            'Im' => [1, 2],
            'mm' => [1, 2],
            'Afc' => [1, 2],
            'Gio' => [1, 2],
            'Clefin/Finance' => [1, 2],
            'Cleli' => [1, 2],
            'Acme' => [1, 2],
            'Des/Ess' => [1, 2],
            'Emit' => [1, 2]
        ];

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
    	foreach (self::COURSES_YEAR_ARRAY as $courses => $years) {
            /** @var $course Course */
            $course = $this->getReference(LoadCourseData::REFERENCE_STRING . $courses);

            foreach ($years as $item) {
                $year = new YearOfStudy();
                $year->setYear($item);
                $year->setCourse($course);
                $manager->persist($year);

                $this->addReference(self::REFERENCE_STRING . $item . $courses, $year);
            }     
        }
    	$manager->flush();
    }
}