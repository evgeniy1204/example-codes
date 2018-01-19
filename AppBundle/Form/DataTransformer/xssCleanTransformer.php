<?php

namespace BetaOmega\AppBundle\Form\DataTransformer;


use Symfony\Component\Form\DataTransformerInterface;
use BetaOmega\AppBundle\Util\xssClean;
use Symfony\Component\Form\Exception\TransformationFailedException;


class xssCleanTransformer implements DataTransformerInterface
{
    public function transform($content)
    {
        if (null === $content) {
            return '';
        }

        return $content;
    }

    public function reverseTransform($content)
    {
        if (!$content) {
            return '';
        }

        $xssFilter = new xssClean();
        $filteringContent = $xssFilter->clean_input($content);

        if (null === $filteringContent) {
            throw new TransformationFailedException();
        }

        return $filteringContent;
    }
}