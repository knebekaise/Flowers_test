<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to create consumer group.
 */
class GroupForm extends Form
{
    /**
     * Current group.
     * @var Application\Entity\Group
     */
    private $group = null;

    /**
     * Scenario ('create' or 'update').
     * @var string
     */
    private $scenario;

    /**
     * Constructor.
     */
    public function __construct($scenario = 'create', $entityManager = null, $group = null)
    {
        // Define form name
        parent::__construct('group-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->group = $group;

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {

        // Add "name" field
        $this->add([
            'type'  => 'text',
            'name' => 'name',
            'attributes' => [
                'id' => 'name'
            ],
            'options' => [
                'label' => 'Group Name',
            ],
        ]);

        // Add the Submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Create'
            ],
        ]);
    }


    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name'     => 'name',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
    }
}
