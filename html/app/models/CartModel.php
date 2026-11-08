<?php

require_once __DIR__ . '/KeranjangModel.php';

/**
 * CartModel (Alias / Turunan dari KeranjangModel)
 * Disediakan agar kompatibel dengan penamaan 'CartModel' di Sequence Diagram Checkout
 */
class CartModel extends KeranjangModel
{
    public function getItemsByUser($userId)
    {
        return $this->getIsiKeranjang($userId);
    }

    public function addItem($userId, $productId, $qty = 1)
    {
        return $this->setJumlahBarang($userId, $productId, $qty);
    }

    public function deleteItem($cartId, $userId)
    {
        return $this->hapusProduk($cartId, $userId);
    }
}
