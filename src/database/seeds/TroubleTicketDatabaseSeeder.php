<?php

namespace Corals\Modules\TroubleTicket\database\seeds;

use Corals\Menu\Models\Menu;
use Corals\Utility\Category\Models\Category;
use Corals\Utility\ListOfValue\Models\ListOfValue;
use Corals\Settings\Models\Setting;
use Corals\User\Communication\Models\NotificationTemplate;
use Corals\User\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TroubleTicketDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TroubleTicketPermissionsDatabaseSeeder::class);
        $this->call(TroubleTicketMenuDatabaseSeeder::class);
        $this->call(TroubleTicketSettingsDatabaseSeeder::class);
        $this->call(TroubleTicketNotificationTemplatesTableSeeder::class);
    }

    public function rollback()
    {
        Permission::where('name', 'like', 'TroubleTicket::%')->delete();
        Permission::where('name', 'Administrations::admin.troubleTicket')->delete();

        Menu::where('key', 'troubleTicket')
            ->orWhere('active_menu_url', 'like', 'trouble-ticket%')
            ->orWhere('url', 'like', 'trouble-ticket%')
            ->delete();

        Setting::where('category', 'TroubleTicket')->delete();

        Media::whereIn('collection_name', ['tt-media-collection'])->delete();

        ListOfValue::query()->where('code', 'tt_status')->delete();
        ListOfValue::query()->where('code', 'tt_priority')->delete();

        NotificationTemplate::where('name', 'like', 'notifications.trouble_tickets.%')->delete();

        Category::query()->where('module', 'TroubleTicket')->delete();
    }
}
