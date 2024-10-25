<?php

namespace Sfneal\ViewModels\Traits;

use Sfneal\Helpers\Laravel\AppInfo;

trait CachingPreferences
{
    /**
     * @var bool Determine if caching has been disabled.
     */
    protected bool $cachingDisabled = false;

    /**
     * Disable ViewModel render caching if the conditional evaluates as true.
     *
     * @param  bool  $conditional
     * @return $this
     */
    public function dontCacheIf(bool $conditional): self
    {
        if ($conditional) {
            $this->cachingDisabled = true;
        }

        return $this;
    }

    /**
     * Disable ViewModel render caching if the app environment is 'development'.
     *
     * @return $this
     */
    public function dontCacheInDevelopment(): self
    {
        if (AppInfo::isEnvDevelopment()) {
            $this->cachingDisabled = true;
        }

        return $this;
    }

    /**
     * Disable ViewModel render caching if the app environment is 'production'.
     *
     * @return $this
     */
    public function dontCacheInProduction(): self
    {
        if (AppInfo::isEnvProduction()) {
            $this->cachingDisabled = true;
        }

        return $this;
    }

    /**
     * Disable ViewModel render caching if the app environment is 'local'.
     *
     * @return $this
     */
    public function dontCacheInLocal(): self
    {
        return $this->dontCacheInEnv('local');
    }

    /**
     * Disable ViewModel render caching if the app environment is 'local'.
     *
     * @return $this
     */
    public function dontCacheInEnv(string $env): self
    {
        if (AppInfo::isEnv($env)) {
            $this->cachingDisabled = true;
        }

        return $this;
    }

    /**
     * Disable caching if the environment is not 'production'
     *
     * @return $this
     */
    public function onlyCacheInProduction(): self
    {
        if (! AppInfo::isEnvProduction()) {
            $this->cachingDisabled = true;
        }

        return $this;
    }
}
