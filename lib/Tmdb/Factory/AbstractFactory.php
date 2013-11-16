<?php
/**
 * This file is part of the Tmdb PHP API created by Michael Roterman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Tmdb
 * @author Michael Roterman <michael@wtfz.net>
 * @copyright (c) 2013, Michael Roterman
 * @version 0.0.1
 */
namespace Tmdb\Factory;

use Tmdb\Exception\RuntimeException;
use Tmdb\Model\AbstractModel;
use Tmdb\Model\Common\QueryParameter\QueryParameterInterface;

abstract class AbstractFactory {
    /**
     * Convert an array to an hydrated object
     *
     * @param array $data
     * @return $this
     */
    abstract public static function create(array $data = array());

    /**
     * Convert an array with an collection of items to an hydrated object collection
     *
     * @param array $data
     * @return $this
     */
    abstract public static function createCollection(array $data = array());

    /**
     * Process query parameters
     *
     * @param array $parameters
     * @return array
     */
    protected function parseQueryParameters(array $parameters = array())
    {
        foreach($parameters as $key => $candidate) {
            if ($candidate instanceof QueryParameterInterface) {
                unset($parameters[$key]);

                $parameters[$candidate->getKey()] = $candidate->getValue();
            }
        }

        return $parameters;
    }

    /**
     * Hydrate the object with data
     *
     * @param AbstractModel $object
     * @param array $data
     * @return $this
     * @throws RuntimeException
     */
    public function hydrate(AbstractModel $object, array $data = array())
    {
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if (in_array($k, $object::$_properties)) {

                    $method = self::camelize(
                        sprintf('set_%s', $k)
                    );

                    if (!method_exists($object, $method)) {
                        throw new RuntimeException(sprintf(
                            'Trying to call method "%s" on "%s" but it does not exist or is private.',
                            $method,
                            get_class($object)
                        ));
                    }

                    $object->$method($v);
                }
            }
        }

        return $object;
    }

    /**
     * Transforms an under_scored_string to a camelCasedOne
     *
     * @see https://gist.github.com/troelskn/751517
     *
     * @param $candidate
     * @return string
     */
    private function camelize($candidate)
    {
        return lcfirst(
            implode('',
                array_map('ucfirst',
                    array_map('strtolower',
                        explode('_', $candidate
                        )
                    )
                )
            )
        );
    }

    /**
     * Transforms a camelCasedString to an under_scored_one
     *
     * @see https://gist.github.com/troelskn/751517
     *
     * @param $camelized
     * @return string
     */
    private function uncamelize($camelized) {
        return implode('_',
            array_map('strtolower',
                preg_split('/([A-Z]{1}[^A-Z]*)/', $camelized, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY)
            )
        );
    }
}