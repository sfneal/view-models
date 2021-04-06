<?php

namespace Sfneal\ViewModels\Tests;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Sfneal\ViewModels\Tests\Mocks\TestViewModel;
use Sfneal\ViewModels\ViewModel;

class ResponsesTest extends TestCase
{
    /** @var ViewModel */
    protected $viewModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->viewModel = new TestViewModel();
    }

    /** @test */
    public function to_response_returns_json_by_default()
    {
        $response = $this->viewModel->toResponse($this->createRequest());

        $this->assertInstanceOf(JsonResponse::class, $response);

        $array = $this->getResponseBody($response);

        $this->assertArrayHasKey('post', $array);
        $this->assertArrayHasKey('categories', $array);
    }

    /** @test */
    public function it_will_return_a_regular_view_when_a_view_is_set_and_a_json_response_is_not_requested()
    {
        $response = $this->viewModel->view('test')->toResponse($this->createRequest());

        $this->assertInstanceOf(Response::class, $response);
    }

    /** @test */
    public function it_will_return_a_json_response_if_a_json_response_is_requested_even_if_a_view_is_set()
    {
        $response = $this->viewModel->view('test')->toResponse($this->createRequest([
            'Accept' => 'application/json',
        ]));

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
