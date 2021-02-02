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
        $viewModel = (new TestViewModel())
            ->view('test')
            ->setRedisKey('http://localhost?page=1');
        $viewModel->render();

        $this->assertTrue($this->viewModel->isCached());
        $this->assertTrue($viewModel->isCached());
    }

    public function test_cache_invalidation()
    {
        $this->viewModel->render();
        $this->viewModel->invalidateCache();
        $this->assertFalse($this->viewModel->isCached());
    }

    public function test_cache_invalidation_nested()
    {
        $this->viewModel->render();

        $viewModel = (new TestViewModel());
        $viewModel
            ->view('test')
            ->setRedisKey('http://localhost?page=1');
        $viewModel->render();

        $viewModel->invalidateCache();

        $this->assertFalse($this->viewModel->isCached());
        $this->assertFalse($viewModel->isCached());
    }

    public function test_cache_invalidation_nested_delete_one()
    {
        $this->viewModel->render();

        $viewModel = (new TestViewModel());
        $viewModel
            ->view('test')
            ->setRedisKey('http://localhost?page=1');
        $viewModel->render();

        $viewModel->invalidateCache(false);

        $this->assertFalse($viewModel->isCached());
        $this->assertTrue($this->viewModel->isCached());
    }

    public function test_cache_key()
    {
        $this->viewModel->render();
        $cacheKey = $this->viewModel->cacheKey();
        $this->assertIsString($cacheKey);
        $this->assertEquals('views:test:0:http://localhost', $cacheKey);
    }

    public function test_redis_key()
    {
        $this->viewModel->render();

        $viewModel = (new TestViewModel());
        $viewModel
            ->view('test')
            ->setRedisKey('http://localhost?page=1')
            ->render();

        $this->assertNotSame($this->viewModel->cacheKey(), $viewModel->cacheKey());
        $this->assertTrue($viewModel->isCached());
    }
}
