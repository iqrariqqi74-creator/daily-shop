<?php

namespace App\Providers;

use App\Repositories\Backend\RoleRepository;
use App\Interfaces\Backend\RoleInterface;

use App\Repositories\Backend\PermissionRepository;
use App\Interfaces\Backend\RolePermissionInterface;

use App\Repositories\Backend\RolePermissionRepository;
use App\Interfaces\Backend\RoleAsPermissionInterface;

use App\Interfaces\Backend\UserInterface;
use App\Repositories\Backend\UserRepository;

use App\Repositories\Backend\VendorRepository;
use App\Interfaces\Backend\VendorInterface;

use App\Repositories\Backend\OrderRepository;
use App\Interfaces\Backend\OrderInterface;

use App\Repositories\Backend\TransactionRepository;
use App\Interfaces\Backend\TransactionInterface;

use App\Interfaces\Backend\CustomerInterface;
use App\Repositories\Backend\CustomerRepository;

use App\Interfaces\Backend\CategoryInterface;
use App\Repositories\Backend\CategoryRepository;

use App\Interfaces\Backend\BrandInterface;
use App\Repositories\Backend\BrandRepository;

use App\Interfaces\Backend\ColorInterface;
use App\Repositories\Backend\ColorRepository;

use App\Interfaces\Backend\SizeInterface;
use App\Repositories\Backend\SizeRepository;

use App\Repositories\backend\ProductRepository;
use App\Interfaces\Backend\ProductInterface;

use App\Repositories\backend\BannerRepository;
use App\Interfaces\Backend\BannerInterface;

use App\Repositories\backend\TaxRepository;
use App\Interfaces\Backend\TaxInterface;

use App\Interfaces\Backend\CouponInterface;
use App\Repositories\backend\CouponRepository;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ✅ Register the RoleRepository with the RoleInterface
        $this->app->bind(RoleInterface::class, RoleRepository::class);
        $this->app->bind(RolePermissionInterface::class, PermissionRepository::class);
        $this->app->bind(RoleAsPermissionInterface::class, RolePermissionRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(CustomerInterface::class, CustomerRepository::class);
        $this->app->bind(VendorInterface::class, VendorRepository::class);
        $this->app->bind(OrderInterface::class, OrderRepository::class);
        $this->app->bind(TransactionInterface::class, TransactionRepository::class);
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(BrandInterface::class, BrandRepository::class);
        $this->app->bind(ColorInterface::class, ColorRepository::class);
        $this->app->bind(SizeInterface::class, SizeRepository::class);
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(BannerInterface::class, BannerRepository::class);
        $this->app->bind(TaxInterface::class, TaxRepository::class);
        $this->app->bind(CouponInterface::class, CouponRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ✅ Fix for Passport static keyPath initialization
        Passport::$keyPath = storage_path();
    }
}