<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        App\Models\Food::class => App\Policies\FoodPolicy::class,
        App\Models\Table::class => App\Policies\TablePolicy::class,
        App\Models\Review::class => App\Policies\ReviewPolicy::class,
        App\Models\Rating::class => App\Policies\RatingPolicy::class,
        App\Models\Promotion::class => App\Policies\PromotionPolicy::class,
        App\Models\Payment::class => App\Policies\PaymentPolicy::class,
        App\Models\OrderDescription::class => App\Policies\OrderDescriptionPolicy::class,
        App\Models\Order::class => App\Policies\OrderPolicy::class,
        App\Models\FoodAllergy::class => App\Policies\FoodAllergyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
