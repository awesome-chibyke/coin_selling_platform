<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Traits\Generics;
use App\Models\Settings;

class SettingsSeed extends Seeder
{

    use Generics;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unique_id = $this->createNewUniqueId('settings', 'unique_id');

        $app_settings = new Settings();
        $app_settings->unique_id = $unique_id;
        $app_settings-> company_name = 'Stacio';
        $app_settings-> email1 = 'dataseller@gmail.com';
        $app_settings-> email2 = 'dataseller2@gmail.com';
        $app_settings-> phone1 = '0123456789';
        $app_settings-> phone2 = '0123456789';
        $app_settings-> address1 = '123 Street, New York, USA';
        $app_settings-> address2 = '123 Street 2, New York, USA';
        $app_settings-> linkedin = 'https://dataseller.com/linkedin';
        $app_settings-> twitter = 'https://dataseller.com/twitter';
        $app_settings-> facebook = 'https://dataseller.com/facebook';
        $app_settings-> instagram = 'https://dataseller.com/instagram';
        $app_settings-> site_url = 'https://dataseller.com';
        $app_settings-> slogan = 'Amazing and Formidable';
        $app_settings->save();
    }
}