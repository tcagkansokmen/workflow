<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\Product;
use App\Core\InventoryLog;

class ProductObserver
{

    /*  Minimum stok için uyarı sistemi  */

    /**
     * Handle the product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
      $product_id = $product->id;

      $inventory = new InventoryLog;
      $inventory->productStock($product_id, $product->quantity, 'yeni ürün', $cost = null, $total_vat = null);
    }

    /**
     * Handle the inventory "updated" event.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }

    /**
     * Handle the inventory "deleted" event.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the inventory "restored" event.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the inventory "force deleted" event.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
