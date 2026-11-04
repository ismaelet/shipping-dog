<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateSaleCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:sale-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Track parcels
     */
    public function handle()
    {
        // * Products
        DB::table('products')->update(['sales' => 0, 'recent_sales' => 0]);

        // Total sales
        DB::update('UPDATE products
		JOIN (
			SELECT products.id, SUM(order_items.quantity) AS total_sales
			FROM products
			JOIN order_items ON products.id = order_items.product_id
			WHERE order_items.status >= 2
			GROUP BY products.id
		) AS sales_table ON products.id = sales_table.id
		SET products.sales = sales_table.total_sales');

        // Recent sales
        DB::update('UPDATE products
		JOIN (
			SELECT products.id, SUM(order_items.quantity) AS total_sales
			FROM products
			JOIN order_items ON products.id = order_items.product_id
			WHERE order_items.status >= 2 AND order_items.created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
			GROUP BY products.id
		) AS sales_table ON products.id = sales_table.id
		SET products.recent_sales = sales_table.total_sales');

        // * Designs
        DB::table('designs')->update(['sales' => 0, 'recent_sales' => 0]);

        // Total sales
        DB::update('UPDATE designs
		JOIN (
			SELECT designs.id, SUM(order_items.quantity) AS total_sales
			FROM designs
			JOIN order_items ON designs.id = order_items.design_id
			WHERE order_items.status >= 2
			GROUP BY designs.id
		) AS sales_table ON designs.id = sales_table.id
		SET designs.sales = sales_table.total_sales');

        // Recent sales
        DB::update('UPDATE designs
		JOIN (
			SELECT designs.id, SUM(order_items.quantity) AS total_sales
			FROM designs
			JOIN order_items ON designs.id = order_items.design_id
			WHERE order_items.status >= 2 AND order_items.created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
			GROUP BY designs.id
		) AS sales_table ON designs.id = sales_table.id
		SET designs.recent_sales = sales_table.total_sales');

        // * Sellers
        DB::table('sellers')->update(['sales' => 0, 'recent_sales' => 0]);

        // Total sales
        DB::update('UPDATE sellers
		JOIN (
			SELECT sellers.id, SUM(order_items.quantity) AS total_sales
			FROM sellers
			JOIN order_items ON sellers.id = order_items.seller_id
			WHERE order_items.status >= 2
			GROUP BY sellers.id
		) AS sales_table ON sellers.id = sales_table.id
		SET sellers.sales = sales_table.total_sales');

        // Recent sales
        DB::update('UPDATE sellers
		JOIN (
			SELECT sellers.id, SUM(order_items.quantity) AS total_sales
			FROM sellers
			JOIN order_items ON sellers.id = order_items.seller_id
			WHERE order_items.status >= 2 AND order_items.created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
			GROUP BY sellers.id
		) AS sales_table ON sellers.id = sales_table.id
		SET sellers.recent_sales = sales_table.total_sales');
    }
}
