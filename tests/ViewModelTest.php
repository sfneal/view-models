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

    /** @test */
    public function render_method_exists()
    {
        $this->assertIsString($this->viewModel->render('test'));
    }
}
