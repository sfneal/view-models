<?php


namespace Sfneal\ViewModels\Traits;


trait PdfViewModel
{
    /**
     * @var bool
     */
    public $forPdf = false;

    /**
     * Declare this ViewModel is being used to create a PDF.
     *
     * @param bool $value
     * @return $this
     */
    public function forPdfOutput(bool $value = true): self
    {
        $this->forPdf = $value;

        return $this;
    }
}
