<?php

namespace BetaOmega\AppBundle\Twig;

use Symfony\Component\Config\Definition\Exception\Exception;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('countYear', array($this, 'getCountYearFromDate')),
            new \Twig_SimpleFilter('sort_by_field', [$this, 'sortByField']),
            new \Twig_SimpleFilter('percent_of_the_number', [$this, 'percentOfTheNumber']),
        );
    }

    /**
     * Count year from date
     * @return int $countYear 
     */
    public function getCountYearFromDate($date)
    {
        if (! $date) {
            return 0;
        }

        $nowDate = new \DateTime();

        $countYear = $nowDate->format('Y') - $date->format('Y');

        return $countYear;
    }

    /**
     * @param array $data
     * @param string $sortField
     * @return array
     */
    public function sortByField(array $data, $sortField)
    {
        $sorted = $sortField ? $this->array_orderby($data, $sortField) : $data;

        return $sorted;
    }

    /**
     * Sorted array by field
     * 
     * @param array $data
     * @param string $sortField
     * @param $sortBy
     */
    public function array_orderby(array $content, $sort_by, $direction = 'DESC')
    {
        if (is_a($content, 'Doctrine\Common\Collections\Collection')) {
            $content = $content->toArray();
        }
        if (!is_array($content)) {
            throw new \InvalidArgumentException('Variable passed to the sortByField filter is not an array');
        } elseif (count($content) < 1) {
            return $content;
        } elseif ($sort_by === null) {
            throw new Exception('No sort by parameter passed to the sortByField filter');
        } elseif (!self::isSortable(current($content), $sort_by)) {
            throw new Exception('Entries passed to the sortByField filter do not have the field "' . $sort_by . '"');
        } else {
            // Unfortunately have to suppress warnings here due to __get function
            // causing usort to think that the array has been modified:
            // usort(): Array was modified by the user comparison function
            @usort($content, function ($a, $b) use ($sort_by, $direction) {
                $flip = ($direction === 'DESC') ? -1 : 1;

                if (is_array($a)) {
                    $a_sort_value = $a[$sort_by];
                } else if (method_exists($a, 'get' . ucfirst($sort_by))) {
                    $a_sort_value = $a->{'get' . ucfirst($sort_by)}();
                    $a_sort_value = is_a($a_sort_value, 'Doctrine\Common\Collections\Collection') ? count($a_sort_value->toArray()) : $a_sort_value;
                } else {
                    $a_sort_value = $a->$sort_by;
                }

                if (is_array($b)) {
                    $b_sort_value = $b[$sort_by];
                } else if (method_exists($b, 'get' . ucfirst($sort_by))) {
                    $b_sort_value = $b->{'get' . ucfirst($sort_by)}();
                    $b_sort_value = is_a($b_sort_value, 'Doctrine\Common\Collections\Collection') ? count($b_sort_value->toArray()) : $b_sort_value;
                } else {
                    $b_sort_value = $b->$sort_by;
                }

                if ($a_sort_value == $b_sort_value) {
                    return 0;
                } else if ($a_sort_value > $b_sort_value) {
                    return (1 * $flip);
                } else {
                    return (-1 * $flip);
                }
            });
        }

        return $content;
    }

    /**
     * 
     */
    public function percentOfTheNumber($data, $percent)
    {
        $percentOfTheNumber = 0;

        if ($data && $percent) {
            $percentOfTheNumber = ($percent * 100) / $data;
        }

        return $percentOfTheNumber;
    }

    /**
     * Validate the passed $item to check if it can be sorted
     * @param $item mixed Collection item to be sorted
     * @param $field string
     * @return bool If collection item can be sorted
     */
    private static function isSortable($item, $field) {
        if (is_array($item)){
            return array_key_exists($field, $item);
        } elseif (is_object($item)) {
            return isset($item->$field) || property_exists($item, $field) || method_exists($item, 'get' . ucfirst($field));
        } else {
            return false;
        }
    }
}