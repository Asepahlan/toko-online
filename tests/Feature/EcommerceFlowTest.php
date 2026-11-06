<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class EcommerceFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cart::clear();
    }

    public function test_homepage_and_catalog_are_accessible()
    {
        $category = Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion',
            'description' => 'Produk fashion berkualitas'
        ]);

        $product = Product::create([
            'name' => 'Baju Keren',
            'slug' => 'baju-keren',
            'description' => 'Deskripsi baju keren',
            'price' => 150000,
            'stock' => 10,
            'category_id' => $category->id,
            'is_active' => true
        ]);

        // Homepage
        $res = $this->get(route('home'));
        $res->assertStatus(200);

        // Products index
        $res = $this->get(route('products.index'));
        $res->assertStatus(200);
        $res->assertSee('Baju Keren');

        // Product show
        $res = $this->get(route('products.show', $product->slug));
        $res->assertStatus(200);
        $res->assertSee('Baju Keren');

        // Categories index
        $res = $this->get(route('categories.index'));
        $res->assertStatus(200);
        $res->assertSee('Fashion');

        // Category show
        $res = $this->get(route('categories.show', $category));
        $res->assertStatus(200);
        $res->assertSee('Baju Keren');
    }

    public function test_cart_operations_work_properly()
    {
        $category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik'
        ]);

        $product = Product::create([
            'name' => 'Mouse Gaming',
            'slug' => 'mouse-gaming',
            'description' => 'Mouse gaming RGB',
            'price' => 200000,
            'stock' => 5,
            'category_id' => $category->id,
            'is_active' => true
        ]);

        // Add to cart
        $res = $this->postJson(route('cart.add', $product->id));
        $res->assertStatus(200);
        $res->assertJson(['success' => true, 'cartCount' => 1]);

        // View Cart
        $res = $this->get(route('cart.index'));
        $res->assertStatus(200);
        $res->assertSee('Mouse Gaming');

        // Update quantity in cart
        $res = $this->postJson("/cart/update/{$product->id}", [
            'quantity' => 2
        ]);
        $res->assertStatus(200);
        $res->assertJson(['success' => true, 'cartCount' => 2]);

        // Remove item from cart
        $res = $this->deleteJson("/cart/remove/{$product->id}");
        $res->assertStatus(200);
        $res->assertJson(['success' => true]);
    }

    public function test_checkout_creates_order_and_reduces_stock()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'phone' => '08123456789',
            'address' => 'Jl. Merdeka No. 10'
        ]);

        $category = Category::create([
            'name' => 'Gadget',
            'slug' => 'gadget'
        ]);

        $product = Product::create([
            'name' => 'Keyboard Mechanical',
            'slug' => 'keyboard-mechanical',
            'description' => 'Blue Switch Mechanical Keyboard',
            'price' => 500000,
            'stock' => 10,
            'category_id' => $category->id,
            'is_active' => true
        ]);

        // Add to cart
        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 2,
            'attributes' => []
        ]);

        // Guest cannot access checkout
        $this->get(route('checkout.index'))->assertRedirect(route('login'));

        // Logged-in user checkout page
        $res = $this->actingAs($user)->get(route('checkout.index'));
        $res->assertStatus(200);
        $res->assertSee('Keyboard Mechanical');

        // Process Checkout
        $res = $this->actingAs($user)->post(route('checkout.process'), [
            'recipient_name' => 'Budi Santoso',
            'phone' => '08987654321',
            'address' => 'Jl. Kebon Jeruk No. 5, Jakarta Barat',
            'notes' => 'Tolong kirim sebelum jam 5 sore'
        ]);

        $order = Order::first();
        $this->assertNotNull($order);
        $res->assertRedirect(route('orders.show', $order->id));

        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals(1000000, $order->total_amount);
        $this->assertEquals('pending', $order->status);
        $this->assertEquals('Jl. Kebon Jeruk No. 5, Jakarta Barat', $order->shipping_address);
        $this->assertEquals('08987654321', $order->contact_number);
        $this->assertCount(1, $order->orderItems);

        // Check stock reduced from 10 to 8
        $product->refresh();
        $this->assertEquals(8, $product->stock);

        // Check cart is empty
        $this->assertTrue(Cart::isEmpty());
    }

    public function test_admin_dashboard_and_reports()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'role' => 'user',
        ]);

        // Regular user blocked from admin
        $this->actingAs($user)->get(route('admin.dashboard'))->assertRedirect(route('home'));

        // Admin can access dashboard
        $res = $this->actingAs($admin)->get(route('admin.dashboard'));
        $res->assertStatus(200);

        // Admin can access reports
        $res = $this->actingAs($admin)->get(route('admin.reports'));
        $res->assertStatus(200);

        // Admin can export sales CSV
        $res = $this->actingAs($admin)->get(route('admin.reports.sales'));
        $res->assertStatus(200);
        $res->assertHeader('Content-Disposition');
    }
}

