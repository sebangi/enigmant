<?php

namespace App\Form;

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

        return strftime("%A %d %B %Y à %H:%M", $datetime->getTimestamp());
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

        setLocale(LC_TIME, 'fr_FR.utf8');
        $champsDate = strptime($datetime, '%A %e %B %Y à %H:%M');
        $str = $champsDate["tm_mday"] . "/" .
                strval((int) $champsDate["tm_mon"] + 1) . "/" .
                strval((int) $champsDate["tm_year"] + 1900) . " " .
                $champsDate["tm_hour"] . ":";
        if ( strlen($champsDate["tm_min"]) == 1 ) {
            $str = $str . "0" . $champsDate["tm_min"];
        } else {
            $str = $str . $champsDate["tm_min"];
        }
        
        return date_create_from_format('j/n/Y G:i', $str, new \DateTimeZone('Europe/Paris'));
    }

}
