<?php

declare(strict_types=1);

namespace Wsi\Utils;

use App\Model\Entity\Product;
use App\Model\Table\ProductsTable;
use Cake\Http\Cookie\CookieCollection;
use Cake\Http\ServerRequest;
use Wsi\PrestashopApi\Dmix\ProductsResource;

class Cart
{
    static function addProductToCookie($productId, $quantity, ServerRequest $request): CookieCollection
    {
        $wsiCookie = $request->getCookieCollection()->get('wsi');

        $wsiCookieValue = $wsiCookie->getValue();

        if (!isset($wsiCookieValue['products_added_to_cart'])) {
            $wsiCookieValue['products_added_to_cart'] = [];
        }

        $addedProducts = $wsiCookieValue['products_added_to_cart'];

        if (isset($addedProducts[$productId])) {
            $quantity = (int) $quantity + (int) $addedProducts[$productId]['quantity'];
        }

        $addedProducts[$productId] = [
            'product_id' => $productId,
            'quantity' => (int) $quantity,
        ];

        $addedProducts = self::calculateTotalsPerProduct($addedProducts);

        $wsiCookieValue['products_added_to_cart'] = $addedProducts;

        $wsiCookie = $wsiCookie->withValue($wsiCookieValue);

        return $request->getCookieCollection()->add($wsiCookie);
    }

    static function deleteProductFromCookie($productId, ServerRequest $request): CookieCollection
    {
        $wsiCookie = $request->getCookieCollection()->get('wsi');

        $wsiCookieValue = $wsiCookie->getValue();

        if (!isset($wsiCookieValue['products_added_to_cart'])) {
            $wsiCookieValue['products_added_to_cart'] = [];
        }

        $addedProducts = $wsiCookieValue['products_added_to_cart'];

        if (!isset($addedProducts[$productId])) {
            return $request->getCookieCollection();
        }

        unset($addedProducts[$productId]);

        $wsiCookieValue['products_added_to_cart'] = $addedProducts;

        $wsiCookie = $wsiCookie->withValue($wsiCookieValue);

        return $request->getCookieCollection()->add($wsiCookie);
    }

    static function calculateTotal(array $addedProducts = [], array $cartDiscounts = [])
    {
        $total = [
            'fraction' => '0',
            'cents' => '00',
            'float' => 0,
        ];

        if ($addedProducts == []) {
            return $total;
        }

        foreach ($addedProducts as $product) {
            $total['float'] += $product['product_total_price']['float'];
        }

        $totalFloat = $total['float'];

        foreach ($cartDiscounts as $key => $discount) {
            switch ($discount['type']) {
                case 'percent':
                    $totalFloat = $totalFloat - ($totalFloat / 100 * $discount['amount']);
                    break;

                case 'amount':
                    $totalFloat = $totalFloat - $discount['amount'];
                    break;

                default:
                    break;
            }
        }

        $totalEntity = new Product(['price' => $totalFloat]);

        $total['fraction'] = $totalEntity->price_fraction;
        $total['cents'] = $totalEntity->price_cents;
        $total['float'] = $totalFloat;

        return $total;
    }

    static function calculateTotalsPerProduct(array $addedProducts)
    {
        if ($addedProducts == []) {
            return $addedProducts;
        }

        $ids = array_keys($addedProducts);

        // $productsTable = new ProductsTable();
        // $products = $productsTable->find()->whereInList('Products.id', $ids)->contain(['DefaultImages'])->toArray();

        $products = (new ProductsResource())->getAll();

        $products = $products->filter(function ($product) use ($ids) {
            return in_array($product->id, $ids);
        });

        $products = $products->toArray();

        foreach ($products as $product) {
            $addedProduct = $addedProducts[$product->id];

            $productTotalPrice = $product->price * $addedProduct['quantity'];

            $productTotalPriceEntity = new Product(['price' => (float) $productTotalPrice]);
            $productTotalPrice = [
                'fraction' => $productTotalPriceEntity->price_fraction,
                'cents' => $productTotalPriceEntity->price_cents,
                'float' => (float) $productTotalPrice,
            ];

            $addedProducts[$product->id] = $addedProduct + ['product_total_price' => $productTotalPrice];
        }

        return $addedProducts;
    }
}
