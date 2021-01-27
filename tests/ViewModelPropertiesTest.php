<?php


namespace Sfneal\ViewModels\Tests;


use Sfneal\ViewModels\AbstractViewModel;
use Sfneal\ViewModels\Tests\Mocks\TestViewModel;

class ViewModelPropertiesTest extends TestCase
{
    /** @var AbstractViewModel */
    protected $viewModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->viewModel = new TestViewModel();
    }

    /** @test */
    public function public_methods_are_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayHasKey('post', $array);
        $this->assertArrayHasKey('categories', $array);
    }

    /** @test */
    public function public_properties_are_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayHasKey('property', $array);
    }

    /** @test */
    public function values_are_kept_as_they_are()
    {
        $array = $this->viewModel->toArray();

        $this->assertEquals('title', $array['post']->title);
    }

    /** @test */
    public function callables_can_be_stored()
    {
        $array = $this->viewModel->toArray();

        $this->assertEquals('foo', $array['callableMethod']('foo'));
    }

    /** @test */
    public function ignored_methods_are_not_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayNotHasKey('ignoredMethod', $array);
    }

    /** @test */
    public function to_array_is_not_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayNotHasKey('toArray', $array);
    }

    /** @test */
    public function to_response_is_not_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayNotHasKey('toResponse', $array);
    }

    /** @test */
    public function magic_methods_are_not_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayNotHasKey('__construct', $array);
    }
}
