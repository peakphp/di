<?php

namespace Peak\Bedrock\View\Form;

use Peak\Bedrock\View\Form\FormBuilder;
use Peak\Bedrock\View\Form\FormDataSet;

/**
 * Form validation wrapper around
 * FormBuilder and FormDataSet
 */
class FormValidation
{
    /**
     * Form builder instance
     * @var \Peak\Bedrock\View\Form\FormBuilder
     */
    protected $form_builder;

    /**
     * Validation dataset
     * @var \Peak\Bedrock\View\Form\FormDataSet
     */
    protected $dataset;

    /**
     * Constructor
     *
     * @param FormBuilder $form_builder
     */
    public function __construct(FormBuilder $form_builder)
    {
        $this->form_builder = $form_builder;
        $this->createDataSet();
    }

    /**
     * Create DataSet
     */
    protected function createDataSet()
    {
        $this->dataset = new FormDataSet();

        foreach ($this->form_builder as $name => $data) {
            if (isset($data['validation']) && is_array($data['validation'])) {
                foreach ($data['validation'] as $rule) {
                    $this->dataset->add($name, $rule);
                }
            }
        }
    }

    /**
     * Validate
     *
     * @param  array $data
     * @return bool
     */
    public function validate($data)
    {
        return $this->dataset->validate($data);
    }

    /**
     * Get validation error
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->dataset->getErrors();
    }
}