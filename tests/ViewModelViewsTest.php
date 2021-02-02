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
    }
    public function test_multiple_views_are_cache()
    {
        foreach ($this->viewModels as $viewModel) {
            $this->assertIsString($viewModel->render());
        }

        foreach ($this->viewModels as $viewModel) {
            $this->assertTrue($viewModel->isCached());
        }

        $keys = [
            'views:test:0:http://localhost',
            'views:test2:0:http://localhost',
            'views:test.test:0:http://localhost',
        ];

        foreach ($keys as $key) {
            $this->assertTrue(RedisCache::exists($key));
        }
    }
}
