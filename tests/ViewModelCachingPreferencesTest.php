<?php

namespace Sfneal\ViewModels\Tests;

use Illuminate\Foundation\Application;
use Sfneal\ViewModels\Tests\Mocks\TestViewModel;
use Sfneal\ViewModels\ViewModel;

class ViewModelCachingPreferencesTest extends TestCase
{
    /** @var ViewModel */
    protected $viewModel;

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('app.env', 'development');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->viewModel = new TestViewModel();
        $this->viewModel->view('test');
    }

    public function test_caching_is_disabled_in_development()
    {
        // Disable caching in dev
        $this->viewModel->dontCacheInDevelopment();

        $this->viewModel->render();

        $this->assertFalse($this->viewModel->isCached());
    }

    public function test_caching_is_disabled_by_conditional()
    {
        // Disable caching in dev
        $this->viewModel->dontCacheIf(true);

        $this->viewModel->render();

        $this->assertFalse($this->viewModel->isCached());
    }

    public function test_caching_is_not_disabled_by_conditional()
    {
        // Disable caching in dev
        $this->viewModel->dontCacheIf(false);

        $this->viewModel->render();

        $this->assertTrue($this->viewModel->isCached());
    }
}
