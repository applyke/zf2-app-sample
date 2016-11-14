<?php
namespace Application\Service;

use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;

class HydratorStrategyService implements HydratorInterface
{
    private $albumHydrator;

    public function __construct()
    {
        $this->albumHydrator = new ClassMethods();
    }

    public function extract($objects)
    {
        $data = $this->albumHydrator->extract($objects);
        return $data;
    }

    public function hydrate(array $data, $object)
    {
        if (isset($data['send'])) {
            unset($data['send']);
        }
        $this->albumHydrator->hydrate($data, $object);
    }
}