<?php

namespace Sfneal\ViewModels\Traits;

trait FormStatus
{
    /**
     * Add success status to the view model.
     *
     * @return $this
     */
    public function withSuccess(): self
    {
        $this->withStatus('success');

        return $this;
    }

    /**
     * Add success status to the view model.
     *
     * @return $this
     */
    public function withFailure(): self
    {
        $this->withStatus('failure');

        return $this;
    }

    /**
     * Set a Client Inquiry success/failure status to be displayed in the view model.
     *
     * @param  string  $status
     * @return $this
     */
    private function withStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
