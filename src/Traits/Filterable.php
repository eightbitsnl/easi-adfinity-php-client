<?php

namespace Eightbitsnl\EasiAdfinityPhpClient\Traits;

use InvalidArgumentException;

trait Filterable
{

    /**
     * A filter operator different from equality operator (=)
     * can be specified between brackets [ ] among the following list :
     * - ne : not equal
     * - lt : less than
     * - lte : less than or equal
     * - gt : greater than
     * - gte : greater than or equal
     * - startswith : starts with
     *
     * In addition to the equality filter operator (no brackets),
     * ne and startswith accepts % in the filter value to match any character.
     * This character must be percent-encoded as %25
     *
     * Example : list all companies from Belgium
     * with a zip code greater than or equal to 1300
     * and city name does NOT contain the word Saint) :
     *
     * /companies?countryCode=BE&zipCode[gte]=1300&cityName[ne]=%25saint%25
     *
     *
     * @param mixed ...$arguments
     * @return self
     */
    public function filter(...$args)
    {

        if(count($args) == 2)
        {
            $this->querystring[] = $args[0] .'='. $args[1];
        }

        elseif(count($args) == 3)
        {
            if(!in_array($args[1], [
                'ne',
                'lt',
                'lte',
                'gt',
                'gte',
                'startswith',
            ])){
                throw new InvalidArgumentException("Unsupported Operator: ". $args[1]);
            }

            $this->querystring[] = $args[0] .'['.$args[1].']='. $args[2];
        }

        else
        {
            throw new InvalidArgumentException("Unsupported argument count");
        }

        return $this;
    }

}

?>
