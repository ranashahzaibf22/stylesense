<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Template::insert(
          [
              ['name'=>'Template 1','file'=>'temp1','created_at'=>now(),'updated_at'=>now(),'image'=>'templates/temp1.jpg'],
              ['name'=>'Template 2','file'=>'temp2','created_at'=>now(),'updated_at'=>now(),'image'=>'templates/temp2.jpg'],
              ['name'=>'Template 3','file'=>'temp3','created_at'=>now(),'updated_at'=>now(),'image'=>'templates/temp3.jpg'],
              ['name'=>'Template 4','file'=>'temp4','created_at'=>now(),'updated_at'=>now(),'image'=>'templates/temp4.jpg'],

          ]
        );
    }
}
