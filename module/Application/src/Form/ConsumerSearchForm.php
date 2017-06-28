<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to search consumer.
 */
class ConsumerSearchForm extends Form
{
    /**
     * Group repository for select field
     * @var Application\Repository\GroupRepository
     */
    private $groupRepository;
    /**
     * Constructor.
     */
    public function __construct($groupRepository)
    {
        // Define form name
        parent::__construct('consumer-search-form');

        // Set POST method for this form
        $this->setAttribute('method', 'get');

        $this->groupRepository = $groupRepository;

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
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
            'name'     => 'expirationDateTime',
            'required' => false,
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
            'required' => false,
        ]);
    }
}
