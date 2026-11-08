<?php

class HomeController extends Controller
{
    public function index()
    {
        $productModel = $this->model('ProductModel');
        $products = $productModel->getAll();

        $data = [
            'title' => 'Beranda - E-Commerce Kelompok 1 RSI',
            'featuredProducts' => array_slice($products, 0, 4)
        ];

        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer');
    }
}
