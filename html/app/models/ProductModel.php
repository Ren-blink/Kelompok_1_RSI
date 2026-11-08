<?php

require_once __DIR__ . '/ProdukModel.php';

/**
 * ProductModel (Alias / Turunan dari ProdukModel)
 * Disediakan agar kompatibel dengan penamaan di 'sd Melakukan Checkout' (ProductModel)
 * dan 'sd Mencari Produk' (ProdukModel).
 */
class ProductModel extends ProdukModel
{
}
