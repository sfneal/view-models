<?php

namespace Sfneal\ViewModels;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Sfneal\Caching\Traits\IsCacheable;
use Sfneal\Helpers\Redis\RedisCache;
use Sfneal\Helpers\Strings\StringHelpers;
use Sfneal\ViewModels\Traits\CachingPreferences;
use Spatie\ViewModels\ViewModel as SpatieViewModel;

abstract class ViewModel extends SpatieViewModel
{
    // todo: make more properties private and add getters/setters
    use CachingPreferences;
    use IsCacheable;

    /**
     * @var int|null Time to live
     */
    public ?int $ttl = null;

    /**
     * @var string|null Use manually declare redis_key (warning: can cause issues with caching)
     */
    public ?string $redis_key = null;

    /**
     * View Directory Prefix.
     */
    public string $prefix;

    /**
     * @var string|null View name
     */
    public $view = null;

    /**
     * Render the View.
     *
     * @return string
     */
    private function __render(): string
    {
        return View::make($this->view, $this->toArray())->render();
    }

    /**
     * Retrieve the authenticated user's ID from the session.
     *
     *  - avoid executing database query
     *  // todo: make this optional, we dont always want to tag cached pages by user
     *
     * @return int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function userId(): int
    {
        try {
            // Find the 'login_web' session key that holds the authenticated user_id value
            // If there's no key containing 'login_web' the user is not logged in
            $session_key = collect(request()->session()->all())->keys()->filter(function ($key) {
                return is_string($key) && (new StringHelpers($key))->inString('login_web');
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
     * // todo: add property?
     *
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function cacheKey(): string
    {
        return 'views'.
            ':'.$this->view.
            ':'.$this->userId().
            ':'.$this->redis_key ?? request()->fullUrl();
    }

    /**
     * Set an override Redis Key.
     *
     * // todo: refactor this
     *
     * @param  string  $redis_key
     * @return $this
     */
    public function setRedisKey(string $redis_key): self
    {
        $this->redis_key = $redis_key;

        return $this;
    }

    /**
     * Retrieve/render the ViewModel from/to the application cache.
     *
     * @param string|null $view
     * @param int|null $ttl
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function render(string $view = null, int $ttl = null): string
    {
        // Don't cache if caching has been disabled
        if ($this->cachingDisabled) {
            return $this->renderNoCache($view);
        }

        // Set $view if it is not null
        if ($view) {
            $this->view = $view;
        }

        // Cache the View if it doesn't exist
        return Cache::remember($this->cacheKey(), $this->getTTL($ttl), function () {
            return $this->__render();
        });
    }

    /**
     * Render the ViewModel without storing or retrieving from the Cache.
     *
     * @param  string|null  $view
     * @return string
     */
    public function renderNoCache(string $view = null): string
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
     * @param bool $children
     * @return $this
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function invalidateCache(bool $children = true): self
    {
        RedisCache::delete($children ? 'views:'.$this->view : $this->cacheKey(), $children);

        return $this;
    }

    /**
     * Return a concatenated route or view name by using the PREFIX const.
     *
     * @param  string  $string
     * @return $this
     */
    public function viewWithPrefix(string $string): self
    {
        $this->view = $this->prefix.$string;

        return $this;
    }

    /**
     * Extend a view name.
     *
     * @param  string  $string
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
     * @param  int|null  $ttl
     * @return int
     */
    public function getTTL(int $ttl = null): int
    {
        return intval($ttl ?? $this->ttl ?? config('redis-helpers.ttl'));
    }

    /**
     * Set the $ttl property during runtime.
     *
     * @param  int  $ttl
     * @return $this
     */
    public function setTTL(int $ttl): self
    {
        $this->ttl = $ttl;

        return $this;
    }

    // todo: fix issues with getTTL & setTTL methods
}
