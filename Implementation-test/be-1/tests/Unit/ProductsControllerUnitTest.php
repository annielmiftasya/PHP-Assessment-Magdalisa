<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductController;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use Mockery;
use Tests\TestCase;

class ProductsControllerUnitTest extends TestCase
{
    protected $productRepository;
    protected $responseFactory;
    protected $productsController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepository = Mockery::mock(ProductRepositoryInterface::class);

        $this->responseFactory = Mockery::mock(ResponseFactory::class);
        $this->responseFactory->shouldReceive('json')->andReturnUsing(function ($data, $status = 200) {
            return new JsonResponse($data, $status);
        });

        $this->productsController = new ProductController($this->productRepository, $this->responseFactory);
    }

    public function testIndex()
    {
        $products = new \Illuminate\Pagination\LengthAwarePaginator(
            [
                (object) ['id' => 1, 'name' => 'Product 1'],
                (object) ['id' => 2, 'name' => 'Product 2']
            ],
            2,
            15,
            1
        );

        $this->productRepository->shouldReceive('getAllPaginated')->once()->with(15)->andReturn($products);

        $request = Request::create('/products', 'GET', ['per_page' => 15]);

        $response = $this->productsController->index($request);

        $responseData = $response->getData(true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('success', $responseData);
        $this->assertTrue($responseData['success']);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertCount(2, $responseData['data']);
        $this->assertArrayHasKey('pagination', $responseData);
        $this->assertArrayHasKey('total', $responseData['pagination']);
        $this->assertEquals(2, $responseData['pagination']['total']);
        $this->assertArrayHasKey('per_page', $responseData['pagination']);
        $this->assertEquals(15, $responseData['pagination']['per_page']);
        $this->assertArrayHasKey('current_page', $responseData['pagination']);
        $this->assertEquals(1, $responseData['pagination']['current_page']);
        $this->assertArrayHasKey('last_page', $responseData['pagination']);
        $this->assertEquals(1, $responseData['pagination']['last_page']);
        $this->assertArrayHasKey('next_page_url', $responseData['pagination']);
        $this->assertNull($responseData['pagination']['next_page_url']);
        $this->assertArrayHasKey('prev_page_url', $responseData['pagination']);
        $this->assertNull($responseData['pagination']['prev_page_url']);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Products retrieved successfully.', $responseData['message']);
    }


    public function testStore()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validate')->andReturn([
            'name' => 'Test Product',
            'description' => 'Product Description',
            'stock' => 10,
            'price' => 100.00
        ]);

        $this->productRepository->shouldReceive('create')->once()->andReturn(true);

        $response = $this->productsController->store($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testShow()
    {
        $product = (object) ['id' => 1, 'name' => 'Product 1'];

        $this->productRepository->shouldReceive('getById')->once()->with(1)->andReturn($product);

        $response = $this->productsController->show(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testUpdate()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validate')->andReturn([
            'name' => 'Updated Product',
            'description' => 'Updated Description',
            'stock' => 15,
            'price' => 150.00
        ]);

        $this->productRepository->shouldReceive('update')->once()->with(1, [
            'name' => 'Updated Product',
            'description' => 'Updated Description',
            'stock' => 15,
            'price' => 150.00
        ])->andReturn(true);

        $response = $this->productsController->update($request, 1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testDestroy()
    {
        $this->productRepository->shouldReceive('delete')->once()->with(1)->andReturn(true);

        $response = $this->productsController->destroy(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
