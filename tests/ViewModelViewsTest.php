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
            (new TestViewModel())->view('test'),
            (new TestViewModel())->view('test2'),
            (new TestViewModel())->view('test.test'),
        ];

        foreach ($this->viewModels as $viewModel) {
            $viewModel->render();
        }
    }
    public function test_multiple_views_are_cache()
    {
        foreach ($this->viewModels as $viewModel) {
            $this->assertTrue($viewModel->isCached());
        }
    }
}
