<?php

namespace TCG\Voyager\Tests;

use Illuminate\Support\Facades\Auth;
use TCG\Voyager\VoyagerServiceProvider;

class PrefixTest extends TestCase
{
    protected $table_prefix = 'voyager_';

    protected function install()
    {
        $this->artisan('voyager:install', [
            '--with-dummy'  => $this->withDummy,
            '--with-prefix' => $this->table_prefix,
        ]);

        app(VoyagerServiceProvider::class, ['app' => $this->app])->loadAuth();

        if (file_exists(base_path('routes/web.php'))) {
            require base_path('routes/web.php');
        }
    }

    /**
     * This test will make sure install with prefix is working and we can
     * visit the dashboard page.
     */
    public function testVisitDashboardPage()
    {
        // We must first login and visit the dashboard page.
        Auth::loginUsingId(1);

        $this->visit(route('voyager.dashboard'))
            ->see(__('voyager::generic.dashboard'));
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        if (file_exists(config_path('voyager.php'))) {
            unlink(config_path('voyager.php'));
        }
    }
}
