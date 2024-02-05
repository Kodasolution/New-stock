<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
    $verticalMenuData = json_decode($verticalMenuJson);
    $horizontalMenuJson = file_get_contents(base_path('resources/menu/horizontalMenu.json'));
    $horizontalMenuData = json_decode($horizontalMenuJson);
    $caisseMenuJson = file_get_contents(base_path('resources/menu/caisseMenu.json'));
    $caisseMenuData = json_decode($caisseMenuJson);
    $cuissinierMenuJson = file_get_contents(base_path('resources/menu/cuissinierMenu.json'));
    $cuissinierMenuData = json_decode($cuissinierMenuJson);

    // Share all menuData to all the views
    View::share('menuData', [$verticalMenuData, $horizontalMenuData,$caisseMenuData,$cuissinierMenuData]);
  }
}
 