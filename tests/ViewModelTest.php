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
    }

    public function test_rendering()
    {
        $this->assertIsString($this->viewModel->render('test'));
    }

    public function test_rendering_no_cache()
    {
        $this->assertIsString($this->viewModel->renderNoCache('test'));
    }

    public function test_rendering_no_cache_vs_cache()
    {
        $this->assertEquals(
            $this->viewModel->renderNoCache('test'),
            $this->viewModel->render('test')
        );
    }

    public function test_rendering_ttl()
    {
        $this->viewModel->render('test');
        $ttl = $this->viewModel->getTTL();

        $this->assertIsInt($ttl);
        $this->assertEquals(config('redis-helpers.ttl'), $ttl);
    }

    public function test_caching()
    {
        $this->viewModel->render('test');
        $this->assertTrue($this->viewModel->isCached());
    }

    public function test_cache_invalidation()
    {
        $this->viewModel->render('test');
        $this->viewModel->invalidateCache();
        $this->assertFalse($this->viewModel->isCached());
    }
}
