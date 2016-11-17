<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form;
use Zend\InputFilter\Factory as FilterFactory;
use Application\Service\HydratorStrategyService;

class AlbumForm extends ApplicationFormAbstract
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setHydrator(new HydratorStrategyService);
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '');
        $this->setAttribute('class', 'form-horizontal');

        $album = null;
        if (isset($options['album'])) {
            /** @var \Application\Entity\Album $album */
            $album = $options['album'];
        }

        $this->add(new Form\Element\Text('title', array(
            'label' => "Title"
        )));
        if (is_object($album)) {
            $this->get('title')->setValue($album->getTitle());
        }

        $this->add(new Form\Element\Text('code', array(
            'label' => "Code"
        )));
        if (is_object($album)) {
            $this->get('code')->setValue($album->getCode());
        }

        $this->add(new Form\Element\Checkbox('status', array(
            'label' => "Activity",
            'checked_value' => 1,
            'unchecked_value' => 0
        )));

        if (is_object($album)) {
            $this->get('status')->setValue($album->getStatus());
        }
        $this->add(array(
            'name' => 'send',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save',
                'class' => 'btn btn-primary'
            ),
        ));

        $inputFilter = $this->inputFilter($options);
        $this->setInputFilter($inputFilter);
    }


    protected function inputFilter($options)
    {
        $factory = new FilterFactory();
        $album = null;

        if (isset($options['album'])) {
            /** @var \Application\Entity\Album $album */
            $album = $options['album'];
        }
        return $factory->createInputFilter(array(
            'title' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                ),
                'validators' => array(
                    new \Zend\Validator\StringLength(array(
                        'min' => 2,
                        'max' => 100
                    )),
                )
            ),
            'code' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\StringTrim(),
                ),
                'validators' => array(
                    new \Zend\Validator\StringLength(array(
                        'min' => 2,
                        'max' => 50
                    )),
                )
            ),
            'status' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\Digits(),
                ),
                'validators' => array(
                    new \Zend\Validator\InArray(array('haystack' => array(0, 1))),
                )
            )
        ));
    }

}
