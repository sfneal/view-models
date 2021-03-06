<?php

namespace Sfneal\ViewModels\Tests;

use Sfneal\ViewModels\Tests\Mocks\TestViewModel;
use Sfneal\ViewModels\ViewModel;

class ViewModelTest extends TestCase
{
    /** @var ViewModel */
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
