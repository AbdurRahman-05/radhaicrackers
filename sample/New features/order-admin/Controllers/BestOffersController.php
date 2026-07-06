<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Services\SaleProductService;
use Illuminate\Http\Request;

class BestOffersController extends Controller
{
    protected $saleProductService;

    public function __construct(SaleProductService $saleProductService)
    {
        $this->saleProductService = $saleProductService;
    }

    /**
     * Get sale products for Best Offers section
     */
    public function getSaleProducts()
    {
        return $this->saleProductService->getTopSaleProducts(5);
    }

    /**
     * Get all sale products for sale page
     */
    public function getAllSaleProducts()
    {
        return $this->saleProductService->getAllSaleProducts();
    }

    /**
     * Show sale products page
     */
    public function index()
    {
        $saleProducts = $this->saleProductService->getAllSaleProducts();
        
        return view('pages.sale-products', compact('saleProducts'));
    }

    /**
     * Get sale products by category
     */
    public function getByCategory($category)
    {
        $saleProducts = $this->saleProductService->getSaleProductsByCategory($category);
        
        return response()->json($saleProducts);
    }
} 