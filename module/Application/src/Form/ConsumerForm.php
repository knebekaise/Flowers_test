<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to create consumer.
 */
class ConsumerForm extends Form
{
    /**
     * Current group.
     * @var Application\Entity\Consumer
     */
    private $consumer = null;

    /**
     * Scenario ('create' or 'update').
     * @var string
     */
    private $scenario;

    /**
     * Group repository for select field
     * @var Application\Repository\GroupRepository
     */
    private $groupRepository;
    /**
     * Constructor.
     */
    public function __construct(
        $groupRepository,
        $scenario = 'create',
        $entityManager = null,
        $consumer = null
    ) {
        // Define form name
        parent::__construct('group-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->groupRepository = $groupRepository;
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->consumer = $consumer;

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {

        // Add "login" field
        $this->add([
            'type'  => 'text',
            'name' => 'login',
            'attributes' => [
                'id' => 'login'
            ],
            'options' => [
                'label' => 'Consumer Login',
            ],
        ]);

        if ($this->scenario == 'create') {
            // Add "password" field
            $this->add([
                'type'  => 'password',
                'name' => 'password',
                'attributes' => [
                    'id' => 'password'
                ],
                'options' => [
                    'label' => 'Consumer Password',
                ],
            ]);
        }

        // Add "email" field
        $this->add([
            'type'  => 'text',
            'name' => 'email',
            'attributes' => [
                'id' => 'email'
            ],
            'options' => [
                'label' => 'Consumer Email',
            ],
        ]);

        // Add "expirationDateTime" field
        $this->add([
            'type'  => 'text',
            'name' => 'expirationDateTime',
            'attributes' => [
                'id' => 'expirationDateTime'
            ],
            'options' => [
                'label' => 'Consumer Expiration Date Time',
            ],
        ]);

        // Add "groupId" field
        $this->add([
            'type'  => 'select',
            'name' => 'groupId',
            'attributes' => [
                'id' => 'groupId'
            ],
            'options' => [
                'label' => 'Consumer Group',
                'value_options' => $this->getGroupList(),
                'empty_option'  => '<Empty>',
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

    protected function getGroupList()
    {
        $data  = $this->groupRepository->findAll();
        $selectData = array();

        foreach ($data as $row) {
            $selectData[$row->getId()] = $row->getName();
        }

        return $selectData;
    }


    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name'     => 'login',
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

        if ($this->scenario == 'create') {
            $inputFilter->add([
                'name'     => 'password',
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

        $inputFilter->add([
            'name'     => 'email',
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
                [
                    'name' => 'EmailAddress',
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'expirationDateTime',
            'required' => true,
            'filters'  => [
                ['name' => 'Int'],
            ],
            'validators' => [
                [
                    'name'    => 'Between',
                    'options' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'groupId',
            'required' => true,
            'filters'  => [
                ['name' => 'Int'],
            ],
        ]);
    }
}
