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
}
