<?php


namespace Sfneal\ViewModels\Interfaces;


interface FormElements
{
    /**
     * Return the form action
     *
     * @return string
     */
    public function action(): string;

    /**
     * Retrieve the form request method
     *
     * @return string
     */
    public function method(): string;

    /**
     * Retrieve the submit button label (usually 'Create' or 'Update')
     *
     * @return string
     */
    public function submit(): string;
}
