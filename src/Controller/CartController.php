<?php

namespace App\Controller;

use App\Model\Entity\Product;
use App\Model\Table\ProductsTable;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Utility\Hash;
use Wsi\PrestashopApi\Dmix\ProductsResource;
use Wsi\PrestashopApi\PrestashopResource;
use Wsi\Utils\Cart;

class CartController extends AppController
{

    public function displayCart()
    {
        $addedProducts = Hash::get($this->request->getCookie('wsi'), 'products_added_to_cart', []);
        $cartDiscounts = Hash::get($this->request->getCookie('wsi'), 'cart_discounts', []);

        if ($addedProducts == []) {
            $this->set(['empty' => true]);
        } else {
            $ids = array_keys($addedProducts);

            // $productsTable = new ProductsTable();
            // $products = $productsTable->find()->whereInList('Products.id', $ids)->contain(['DefaultImages'])->toArray();

            $products = (new ProductsResource())->getAll();
            $products = $products->filter(function ($product) use ($ids) {
                return in_array($product->id, $ids);
            });

            $total = 0;
            $totalQuantity = 0;

            foreach ($products as $product) {
                $addedProduct = $addedProducts[$product->id];

                $productTotalPrice = $product->price * $addedProduct['quantity'];

                $total += $productTotalPrice;
                $totalQuantity += $addedProduct['quantity'];

                $productTotalPriceEntity = new Product(['price' => (float) $productTotalPrice]);
                $productTotalPrice = [
                    'fraction' => $productTotalPriceEntity->price_fraction,
                    'cents' => $productTotalPriceEntity->price_cents,
                ];

                $addedProducts[$product->id] = $addedProduct + ['product_entity' => $product, 'product_total_price' => $productTotalPrice];
            }

            $totalFloat = (float) $total;

            foreach ($cartDiscounts as $discount) {
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
            $total = [
                'fraction' => $totalEntity->price_fraction,
                'cents' => $totalEntity->price_cents,
            ];

            $this->set(['empty' => false]);
            $this->set(compact('total', 'addedProducts', 'totalQuantity', 'cartDiscounts'));
        }

        return $this->render('cart');
    }

    public function addToCart()
    {
        $post = $this->request->getParsedBody();

        $productId = $post['product_id'];
        $quantity = $post['quantity'];

        $newCookieCollection = Cart::addProductToCookie($productId, $quantity, $this->request);

        $this->response = $this->response->withCookieCollection($newCookieCollection);

        $newTotal = Cart::calculateTotal($newCookieCollection->get('wsi')->getValue()['products_added_to_cart']);

        return $this->response->withType('json')->withStringBody(json_encode(['product_added' => TRUE, 'new_total' => $newTotal]));
    }

    public function deleteItem($productId)
    {
        $newCookieCollection = Cart::deleteProductFromCookie($productId, $this->request);

        $this->response = $this->response->withCookieCollection($newCookieCollection);

        $this->request = $this->request->withCookieCollection($newCookieCollection);

        return $this->displayCart();
    }

    public function toCheckout()
    {
        $addedProducts = Hash::get($this->request->getCookie('wsi'), 'products_added_to_cart', []);
        $cartDiscounts = Hash::get($this->request->getCookie('wsi'), 'cart_discounts', []);

        $cart = [
            'id_currency' => 1,
            'id_lang' => 2,
            'associations' => [
                'cart_rows' => []
            ]
        ];

        foreach ($addedProducts as $id => $addedProduct) {
            $cart['associations']['cart_rows'][] = [
                'id_product' => $id,
                'quantity' => $addedProduct['quantity'],
                'id_product_attribute' => 0,
            ];
        }

        $carts = new PrestashopResource('carts');

        $newCart = $carts->addCart($cart);

        $cartId = $newCart['id'];

        $http = new Client();

        if ($cartDiscounts) {
            $discountIds = array_keys($cartDiscounts);
            $discountIds = implode(',', $discountIds);
            $psResponse = $http->post(Configure::read('Prestashop.url') . '/dmix', ['dmix_wsi_discount' => $discountIds, 'dmix_wsi_cart' => $cartId]);
        }

        return $this->redirect(Configure::read('Prestashop.url') . '/dmix?dmix_with_cart=' . $cartId);
    }

    public function applyDiscount()
    {
        $code = $this->request->getParsedBody()['code'];

        $psResource = new PrestashopResource('cart_rules');
        $psResource->applyFilter('code', $code);
        $psResource->applyFilter('active', '1');

        $response = $psResource->getAll();

        if ($response == []) {
            $errors = $this->viewBuilder()->getVar('errors');
            $errors = $errors ? $errors : [];
            $errors['invalid_discount_code'] = TRUE;
            $this->set(['errors' => $errors]);
        } else {
            $cart_rule = $response[array_key_first($response)];

            $cookieCollection = $this->request->getCookieCollection();
            $wsiCookie = $cookieCollection->get('wsi');
            $cookieValue = $wsiCookie->getValue();

            $discounts = isset($cookieValue['cart_discounts']) ? $cookieValue['cart_discounts'] : [];

            if (isset($discounts[$cart_rule['id']])) {
                return $this->redirect('/carrito');
            }

            $discounts[$cart_rule['id']] = [
                'type' => $cart_rule['reduction_percent'] >= $cart_rule['reduction_amount'] ? 'percent' : 'amount',
            ];
            $discounts[$cart_rule['id']]['amount'] = $cart_rule['reduction_' . $discounts[$cart_rule['id']]['type']];

            $cookieValue['cart_discounts'] = $discounts;
            $wsiCookie = $wsiCookie->withValue($cookieValue);

            $this->setRequest($this->request->withCookieCollection($cookieCollection->add($wsiCookie)));
            $this->setResponse($this->response->withCookieCollection($cookieCollection->add($wsiCookie)));
        }

        return $this->displayCart();
    }
}
