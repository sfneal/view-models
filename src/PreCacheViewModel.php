<?php

namespace Sfneal\ViewModels;

use Sfneal\Queueables\Job;

class PreCacheViewModel extends Job
{
    /**
     * @var int Number of seconds to delay dispatching by
     */
    public $delay = 30;

    /**
     * @var string Queue to use
     */
    public $queue = 'cache';

    /**
     * @var ViewModel
     */
    private ViewModel $viewModel;

    /**
     * @var string
     */
    private string $route_name;

    /**
     * @var array|null
     */
    private ?array $route_data;

    /**
     * PreCacheViewModel constructor.
     *
     * @param  $viewModel
     * @param  string  $route_name
     * @param  array|null  $route_data
     */
    public function __construct($viewModel, string $route_name, array $route_data = null)
    {
        $this->viewModel = $viewModel;
        $this->route_name = $route_name;
        $this->route_data = $route_data;
    }

    /**
     * Send a GuzzleHttp get request (intended for pre-caching a views).
     *
     * @return string
     */
    public function handle(): string
    {
        return $this->viewModel
            ->setRedisKey(route($this->route_name, $this->route_data))
            ->invalidateCache()
            ->render();
    }
}
