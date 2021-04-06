<?php

namespace Sfneal\ViewModels\Tests;

use Illuminate\Support\Facades\Queue;
use Sfneal\ViewModels\ViewModel;
use Sfneal\ViewModels\PreCacheViewModel;
use Sfneal\ViewModels\Tests\Mocks\TestViewModel;

class PreCacheViewModelTest extends TestCase
{
    /** @var ViewModel */
    protected $viewModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->viewModel = new TestViewModel();
        $this->viewModel->view('test');
    }

    public function test_pre_cache()
    {
        PreCacheViewModel::dispatchNow($this->viewModel, 'test');

        $this->assertTrue($this->viewModel->isCached());
        $this->assertIsString($this->viewModel->cacheKey());
    }

    public function test_pre_cache_queued()
    {
        // Enable queue faking
        Queue::fake();

        // Assert that no jobs were pushed...
        Queue::assertNothingPushed();

        // Dispatch the first job...
        Queue::push(new PreCacheViewModel($this->viewModel, 'test'));

        // Assert a job was pushed...
        Queue::assertPushed(PreCacheViewModel::class, 1);
    }

    public function test_pre_cache_is_the_same_as_rendered()
    {
        $renderNoCache = $this->viewModel->renderNoCache();
        PreCacheViewModel::dispatchNow($this->viewModel, 'test');
        $render = $this->viewModel->render();

        $this->assertIsString($renderNoCache);
        $this->assertIsString($render);
        $this->assertEquals($renderNoCache, $render);
    }
}
