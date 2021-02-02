<?php

namespace Sfneal\ViewModels\Tests;

use Sfneal\Helpers\Redis\RedisCache;
use Sfneal\ViewModels\Tests\Mocks\TestViewModel;

class ViewModelViewsTest extends TestCase
{
    /**
     * @var array
     */
    protected $viewModels;

    protected function setUp(): void
    {
        parent::setUp();

        $this->viewModels = [
            0 => (new TestViewModel())->view('test'),
            1 => (new TestViewModel())->view('test')->setRedisKey('http://localhost?page=1'),
            2 => (new TestViewModel())->view('test')->setRedisKey('http://localhost?page=2'),
            3 => (new TestViewModel())->view('test2'),
            4 => (new TestViewModel())->view('test2')->setRedisKey('http://localhost?page=1'),
            5 => (new TestViewModel())->view('test2')->setRedisKey('http://localhost?page=2'),
            6 => (new TestViewModel())->view('test.test'),
            7 => (new TestViewModel())->view('test.test')->setRedisKey('http://localhost?page=1'),
            8 => (new TestViewModel())->view('test.test')->setRedisKey('http://localhost?page=2'),
        ];

        foreach ($this->viewModels as $viewModel) {
            $viewModel->render();
        }
    }

    public function test_caching_multiple_views()
    {
        foreach ($this->viewModels as $viewModel) {
            $this->assertTrue($viewModel->isCached());
        }
    }

    public function test_deleting_multiple_views()
    {
        $this->viewModels[3]->invalidateCache();

        $this->assertFalse($this->viewModels[3]->isCached());
        $this->assertFalse($this->viewModels[4]->isCached());
        $this->assertFalse($this->viewModels[5]->isCached());
    }

    public function test_deleting_single_view_child()
    {
        $this->viewModels[4]->invalidateCache(false);

        $this->assertFalse($this->viewModels[4]->isCached());
        $this->assertTrue($this->viewModels[3]->isCached());
        $this->assertTrue($this->viewModels[5]->isCached());
    }

    public function test_deleting_single_view_parent()
    {
        $this->viewModels[3]->invalidateCache(false);

        $this->assertFalse($this->viewModels[3]->isCached());
        $this->assertTrue($this->viewModels[4]->isCached());
        $this->assertTrue($this->viewModels[5]->isCached());
    }
}
