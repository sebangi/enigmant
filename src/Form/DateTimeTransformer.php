<?php

namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateTimeTransformer implements DataTransformerInterface {

    /**
     * Transforms an object (DateTime) to a string.
     *
     * @param  DateTime|null $datetime
     * @return string
     */
    public function transform($datetime) {
        if (null === $datetime) {
            return '';
        }

        date_default_timezone_set('Europe/Paris');
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        return strftime("%A %d %B %Y %H:%M", $datetime->getTimestamp());
    }

    /**
     * Transforms a string to an object (DateTime).
     *
     * @param  string $datetime
     * @return DateTime|null
     */
    public function reverseTransform($datetime) {
        // datetime optional
        if (!$datetime) {
            return;
        }

        return date_create_from_format('A d B Y H:M', $datetime, new \DateTimeZone('Europe/Madrid'));
    }

}
