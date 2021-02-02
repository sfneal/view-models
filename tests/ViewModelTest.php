<?php

namespace Sfneal\ViewModels\Tests;

use Sfneal\ViewModels\AbstractViewModel;
use Sfneal\ViewModels\Tests\Mocks\TestViewModel;

class ViewModelTest extends TestCase
{
    /** @var AbstractViewModel */
    protected $viewModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->viewModel = new TestViewModel();
        $this->viewModel->view('test');
    }

    public function test_rendering()
    {
        $this->assertIsString($this->viewModel->render());
    }

    public function test_rendering_no_cache()
    {
        $this->assertIsString($this->viewModel->renderNoCache());
    }

    public function test_rendering_no_cache_vs_cache()
    {
        $this->assertEquals(
            $this->viewModel->renderNoCache(),
            $this->viewModel->render()
        );
    }

    public function test_rendering_ttl()
    {
        $this->viewModel->render();
        $ttl = $this->viewModel->getTTL();

        $this->assertIsInt($ttl);
        $this->assertEquals(config('redis-helpers.ttl'), $ttl);
    }

    public function test_caching()
    {
        $this->viewModel->render();
        $this->assertTrue($this->viewModel->isCached());
    }

    public function test_cache_invalidation()
    {
        $this->viewModel->render();
        $this->viewModel->invalidateCache();
        $this->assertFalse($this->viewModel->isCached());
    }

    public function test_cache_key()
    {
        $this->viewModel->render();
        $cacheKey = $this->viewModel->cacheKey();
        $this->assertIsString($cacheKey);
        $this->assertEquals($cacheKey, 'views:test#0#http://localhost');
    }
}
