<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Routing\Route;

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
      View::composer('*', function ($view) {
          $user = Auth::user();

          if ($user) {
              $dataMenu = [];

              $dataHeader = [];
              $dataSubHeader = [];
              $menu = [];

              $dataHalaman = session('akses_default_halaman');
              if (!$dataHalaman) {
                  $dataHalaman = [];
                  $dataHeader = [];
              }

              foreach ($dataHalaman as $halaman){
                  if (!is_null($halaman->halaman->id_header)){
                      if (empty($dataHeader) OR !in_array($halaman->halaman->id_header, array_column($dataHeader, 'id_header'))){
                          $dataHeader[] = ['id_header' => $halaman->halaman->id_header, 'nama' => $halaman->halaman->header->nama];
                      }
                  }

                  if (!is_null($halaman->halaman->id_subheader)){
                      if (!isset($dataSubHeader[$halaman->halaman->id_header])){
                          $dataSubHeader[$halaman->halaman->id_header] = [];
                      }

                      if (!isset($menu[$halaman->halaman->id_subheader])){
                          $menu[$halaman->halaman->id_subheader] = [];
                      }

                      if (empty($dataSubHeader) OR !in_array($halaman->halaman->id_subheader, array_column($dataSubHeader[$halaman->halaman->id_header], 'id_subheader'))){
                          $dataSubHeader[$halaman->halaman->id_header][] = [
                              'id_subheader' => $halaman->halaman->id_subheader,
                              'id_uniqsubheader' => 'sub'.$halaman->halaman->id_subheader,
                              'id_header' => $halaman->halaman->id_header,
                              'nama' => $halaman->halaman->subheader->nama,
                              'icon' => $halaman->halaman->subheader->icon,
                              'slug' => $halaman->halaman->subheader->slug,
                              'badge' => $halaman->halaman->subheader->badge
                          ];
                      }

                      $menu['sub'.$halaman->halaman->id_subheader][] = [
                          'id_halaman' => $halaman->halaman->id_halaman,
                          'nama' => $halaman->halaman->nama,
                          'icon' => $halaman->halaman->icon,
                          'slug' => $halaman->halaman->slug,
                          'url' => $halaman->halaman->url,
                          'badge' => $halaman->halaman->badge
                      ];
                  }else{
                      if (!isset($dataSubHeader[$halaman->halaman->id_header])){
                          $dataSubHeader[$halaman->halaman->id_header] = [];
                      }

                      $dataSubHeader[$halaman->halaman->id_header][] = [
                          'id_halaman' => $halaman->halaman->id_halaman,
                          'id_uniqsubheader' => -1,
                          'nama' => $halaman->halaman->nama,
                          'icon' => $halaman->halaman->icon,
                          'slug' => $halaman->halaman->slug,
                          'url' => $halaman->halaman->url,
                          'badge' => $halaman->halaman->badge
                      ];
                  }
              }

//              dd($dataHeader, $dataSubHeader, $menu, $dataMenu);

              foreach ($dataHeader as $header){
                  $dataMenu[] = [
                      'menuHeader' => $header['nama'],
                  ];

                  foreach ($dataSubHeader[$header['id_header']] as $subHeader){
                      if (isset($menu[$subHeader['id_uniqsubheader']])){
                          $dataMenu[] = [
                              "name" => $subHeader['nama'],
                              "icon" => $subHeader['icon'],
                              "slug" => $subHeader['slug'],
                              "submenu" => $menu[$subHeader['id_uniqsubheader']]
                          ];
                      }else{
                          $dataMenu[] = [
                              "url" => $subHeader['url'],
                              "name" => $subHeader['nama'],
                              "icon" => $subHeader['icon'],
                              "slug" => $subHeader['slug'],
                              "badge" => $subHeader['badge']
                          ];
                      }
                  }
              }

              //dd($dataHeader, $dataSubHeader, $menu, $dataMenu);

              $dataMenu = ['menu' => $dataMenu];
              $dataMenu = json_decode(json_encode($dataMenu));
              // Bagikan data ke semua view

              $view->with('menuData', [$dataMenu]);
          }
      });
//    $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
//    $verticalMenuData = json_decode($verticalMenuJson);
//
//    // Share all menuData to all the views
//    $this->app->make('view')->share('menuData', [$verticalMenuData]);
  }
}
