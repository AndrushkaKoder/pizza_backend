<?php

namespace App\Http\Controllers\Api\V1\Interfaces;

/**
 * @OA\Get (
 *     path="api/v1/products",
 *     summary="Получение списка продуктов",
 *     tags={"Product"},
 *
 *     @OA\RequestBody(),
 *     @OA\Response(
 *         response=200,
 *         description="Ok"
 *     ),
 * ),
 * @OA\Get (
 *      path="api/v1/product/{id}",
 *      summary="Получение продукта",
 *      tags={"Product"},
 *
 *      @OA\RequestBody(),
 *      @OA\Response(
 *          response=200,
 *          description="Ok",
 *          @OA\JsonContent(
 *              @OA\Property(property="id", type="int"),
 *              @OA\Property(property="title", type="string"),
 *              @OA\Property(property="description", type="string"),
 *              @OA\Property(property="weight", type="int"),
 *              @OA\Property(property="price", type="int"),
 *              @OA\Property(property="discount", type="int"),
 *              @OA\Property(property="categories", type="object"),
 *              @OA\Property(property="images", type="object"),
 *          ),
 *      ),
 *  ),
 */

interface ProductsControllerInterface
{
}
