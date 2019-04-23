<?php

class CartController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->models->cart = new CartModel();
        $this->models->order = new OrderModel();
        $this->models->product = new ProductModel();
        $this->models->user = new UserModel();
        $this->models->photo = new PhotoModel();
    }

    public function api_check()
    {
        $cart = urldecode(trim($_POST["cart"]));
        $cart = json_decode($cart);
        if (json_last_error() != JSON_ERROR_NONE) return;

        $products = [];
        foreach ($cart as $cartProduct) {
            $productID = $cartProduct->id;
            $productCol = $cartProduct->col;

            $product = $this->models->product->get($productID);

            if (!$product) continue;
            if ($productCol < 1) $cartProduct->col = 1;

            if ($productCol > $product["col"])
                $cartProduct->col = $product["col"];

            $newProductItem = $cartProduct;
            $newProductItem->product = $product;
            $products[] = $newProductItem;
        }

        echo json_encode($products);
        exit();
    }

}