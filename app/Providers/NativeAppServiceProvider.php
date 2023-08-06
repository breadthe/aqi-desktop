<?php

namespace App\Providers;

use Native\Laravel\Facades\ContextMenu;
use Native\Laravel\Facades\Dock;
use Native\Laravel\Facades\MenuBar;
use Native\Laravel\Facades\Window;
use Native\Laravel\Facades\GlobalShortcut;
use Native\Laravel\Menu\Menu;

class NativeAppServiceProvider
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Menu::new()
            ->appMenu()
            ->fileMenu()
            ->editMenu()
            /*->submenu('View', Menu::new()
                ->toggleFullscreen()
                ->separator()
                ->link('https://laravel.com', 'Learn More', 'CmdOrCtrl+L')
            )*/
            ->submenu(
                'About',
                Menu::new()
                    ->link(config('nativephp.author_url'), config('nativephp.author'))
            )
            ->register();

        MenuBar::create()
            // ->alwaysOnTop()
            ->width(400)
            ->height(420)
            ->minWidth(400)
            ->minHeight(420)
            ->maxWidth(400)
            ->maxHeight(420)
            ->route('location')
            ->showDockIcon();

        /*Window::open()
            ->alwaysOnTop()
            ->rememberState()
            ->width(400)
            ->height(400)
            ->minWidth(400)
            ->minHeight(400)
            ->maxWidth(400)
            ->maxHeight(400);*/

        /**
            Dock::menu(
                Menu::new()
                    ->event(DockItemClicked::class, 'Settings')
                    ->submenu('Help',
                        Menu::new()
                            ->event(DockItemClicked::class, 'About')
                            ->event(DockItemClicked::class, 'Learn More…')
                    )
            );

            ContextMenu::register(
                Menu::new()
                    ->event(ContextMenuClicked::class, 'Do something')
            );

            GlobalShortcut::new()
                ->key('CmdOrCtrl+Shift+I')
                ->event(ShortcutPressed::class)
                ->register();
        */
    }
}
