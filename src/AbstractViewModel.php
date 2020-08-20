<?php

namespace Sfneal\ViewModels;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use RuntimeException;
use Spatie\ViewModels\ViewModel;

abstract class AbstractViewModel extends ViewModel
{
    /**
     * @var int Time to live
     */
    public $ttl = null;

    /**
     * @var string Use manually declare redis_key (warning: can cause issues with caching)
     */
    public $redis_key = null;

    /**
     * View Directory Prefix.
     */
    public $prefix;

    /**
     * @var null View name
     */
    public $view = null;

    /**
     * Render the View.
     *
     * @return string
     */
    private function __render()
    {
        return View::make($this->view, $this->toArray())->render();
    }

    /**
     * Retrieve the authenticated user's ID from the session.
     *
     *  - avoid executing database query
     *
     * @return int
     */
    private function userId(): int
    {
        try {
            // Find the 'login_web' session key that holds the authenticated user_id value
            // If there's no key containing 'login_web' the user is not logged in
            $session_key = collect(request()->session()->all())->keys()->filter(function ($key) {
                return is_string($key) && inString($key, 'login_web');
            })->first();
        } catch (RuntimeException $runtimeException) {
            // request and/or session is not set (called from a job)
            $session_key = 0;
        }

        // Get the $session_key if it's not null
        return ! empty($session_key) ? session()->get($session_key) : 0;
    }

    /**
     * Retrieve a unique redis key for caching the view.
     *
     * @return string
     */
    private function redisViewKey(): string
    {
        return 'views'.
            ':'.$this->view.
            '#'.$this->userId().
            '#'.(isset($this->redis_key) ? $this->redis_key : request()->fullUrl());
    }

    /**
     * Set an override Redis Key.
     *
     * @param string $redis_key
     * @return $this
     */
    public function setRedisKey(string $redis_key)
    {
        $this->redis_key = $redis_key;

        return $this;
    }

    /**
     * Retrieve/render the ViewModel from/to the application cache.
     *
     * @param string|null $view
     * @param int|null $ttl
     * @return Response|string|mixed
     */
    public function render(string $view = null, int $ttl = null)
    {
        // Set $view if it is not null
        if ($view) {
            $this->view = $view;
        }

        // Cache the View if it doesn't exist
        return redisRemember($this->redisViewKey(), $this->getTTL($ttl), function () {
            return $this->__render();
        });
    }

    /**
     * Render the ViewModel without storing or retrieving from the Cache.
     *
     * @param string|null $view
     * @return Response|string|mixed
     */
    public function renderNoCache(string $view = null)
    {
        // Set $view if it is not null
        if ($view) {
            $this->view = $view;
        }

        return $this->__render();
    }

    /**
     * Invalidate the View Cache for this ViewModel.
     *
     * @return $this
     */
    public function invalidateCache()
    {
        redisDelete('views:'.$this->view);

        return $this;
    }

    /**
     * Return a concatenated route or view name by using the PREFIX const.
     *
     * @param string $string
     * @return $this
     */
    public function viewWithPrefix(string $string)
    {
        $this->view = $this->prefix.$string;

        return $this;
    }

    /**
     * Extend a view name.
     *
     * @param string $string
     * @return $this
     */
    public function viewExtend(string $string): self
    {
        $this->view .= $string;

        return $this;
    }

    /**
     * Retrieve the time to live for the cached values
     *  - 1. passed $ttl parameter
     *  - 2. initialized $this->ttl property
     *  - 3. application default cache ttl.
     *
     * @param int|null $ttl
     * @return int|mixed
     */
    private function getTTL(int $ttl = null)
    {
        return $ttl ?? $this->ttl ?? env('REDIS_KEY_EXPIRATION');
    }
}
