<?php

declare(strict_types=1);

namespace App\Http\Services\Products;

use App\Exceptions\ExternalApiException;
use GuzzleHttp\Client;

class ProductsRepository
{

    private readonly string $productsPath;

    public function __construct(private readonly Client $httpClient)
    {
        $this->productsPath = env('PRODUCTS_WEB_PATH');
    }

    public function getProducts(): array
    {
        try {
            $request = $this->httpClient->request('GET', $this->productsPath . '/products');
            return json_decode($request->getBody()->getContents(), true);
        } catch (ExternalApiException $exception) {
            return [];
        }
    }

    public function getProduct(int $id): array
    {
        try {
            $request = $this->httpClient->request('GET', $this->productsPath . '/products/' . $id);
            return json_decode($request->getBody()->getContents(), true);
        } catch (ExternalApiException $exception) {
            return [];
        }
    }
}
