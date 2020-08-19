<?php

namespace Sfneal\ViewModels;

abstract class AbstractPublicViewModel extends AbstractViewModel
{
    public $limit = null;
    public $learn_more = false;
    public $public = true;

    public function __construct($limit=100, $learn_more=false, $public=true)
    {
        $this->limit = $limit;
        $this->learn_more = $learn_more;

        $this->public = $public;
        $this->view = ($public == true)?"public.{$this->view}":"{$this->view}";
    }
}
