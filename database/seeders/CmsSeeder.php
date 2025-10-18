<?php

namespace Database\Seeders;

use App\Models\Cms;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Cms::truncate();
        Cms::create([
            'page' => 'step-1',
            'data' => [
                'heading_one' => 'Welcome to the registration portal for Dubai Fashion Week, SS24 08-15 October 2023, founded by Dubai Design District and Arab Fashion Council',
                'step_title' => 'STEP 1/6',
                'heading_two' => 'To start the process please type your mobile number and make sure you choose the correct country.'
            ]
        ]);
           Cms::create([
            'page' => 'step-1-simplified',
            'data' => [
                'heading_one' => 'Welcome to the registration portal for Dubai Fashion Week, SS24 08-15 October 2023, founded by Dubai Design District and Arab Fashion Council',
                'step_title' => 'STEP 1/6',
                'heading_two' => 'To start the process please type your mobile number and make sure you choose the correct country.'
            ]
        ]);

        Cms::create([
            'page' => 'step-2',
            'data' => [
                'heading_one' => 'Is your WhatsApp number the same as your mobile number?',
                'step_title' => 'STEP 2/6',
                'heading_two' => 'If the answer is No, please insert below your WhatsApp number'
            ]
        ]);

        Cms::create([
            'page' => 'step-3',
            'data' => [
                'heading_one' => 'Please type your email address',
                'step_title' => 'STEP 3/6',
            ]
        ]);

        Cms::create([
            'page' => 'step-4',
            'data' => [
                'heading_one' => 'Please select your profile category*',
                'step_title' => 'STEP 4/6',
            ]
        ]);

        Cms::create([
            'page' => 'step-5',
            'data' => [
                'step_title' => 'STEP 5/6',
                'field_1' => 'First Name',
                'field_2' => 'Last Name',
                'field_3' => 'Email',
                'field_4' => 'Phone',
                'field_5' => 'Company, you represent',
                'field_6' => 'Link to one article you have written',
                'field_7' => 'Work e-mail address',
                'field_8' => 'Please insert at least your LinkedIn or Instagram link',
                'field_9' => 'linkedin',
                'field_10' => ' Instagram',
                'field_11' => 'Invitation Code (Leave it blank if you haven’t received an invitation code)',
                'field_12' => 'Store, you represent (If more than one store please insert comma (,) between each store',
                'field_13' => 'Work e-mail address, if different from the above',
                'field_14' => 'Which category you are interested in?',
                'field_15' => 'Link to your portfolio or website',
                'field_16' => 'What type of photography you practice?',
            ]
        ]);

           Cms::create([
            'page' => 'step-6',
            'data' => [
                'heading_one' => 'Now select the shows or events you’d like to attend.',
                'step_title' => 'STEP 6/6',
            ]
        ]);

        Cms::create([
            'page' => 'header',
            'data' => [
                'image' => 'uploads/cms/DFW-LOGO-WEB.png',
                'list1' => 'HOME',
                'list1_url' => 'https://dubaifashionweek.org/',
                'list2' => 'CALENDAR',
                'list2_url' => 'https://dubaifashionweek.org/calendar/',
            ]
        ]);

        Cms::create([
            'page' => 'footer',
            'data' => [
               'heading_one'=>'Keep IN TOUCH',
               'list_1'=>'About',
               'list_1_url'=>'https://dubaifashionweek.org/about',
               'list_2' => 'Contact',
               'list_2_url' => 'https://dubaifashionweek.org/contact-us',
               'list_3' => 'Buyers&Press',
               'list_3_url' => 'https://dubaifashionweek.org/registration/',
               'list_4' => 'Partners',
               'list_4_url' => 'https://dubaifashionweek.org/sponsors/',
               'list_5' => 'Sponsors Applications',
               'list_5_url' => 'https://dubaifashionweek.org/sponsors-apply-now/',
               'list_6' => 'Designers Applications',
               'list_6_url' => 'https://dubaifashionweek.org/designers-application/',
               'list_7' => 'Privacy Policy',
               'list_7_url' => 'https://dubaifashionweek.org/privacy-policy/',
               'list_8' => 'Terms & Conditions',
               'list_8_url' => 'https://dubaifashionweek.org/terms-of-use/',
               'list_9' => '© Dubai Fashion Week 2023',
               'list_9_url' => 'https://dubaifashionweek.org/',
               'instagram_url'=>'https://www.instagram.com/dubaifashionweek/',
               'facebook_url'=>'https://www.facebook.com/ArabFashionWeek/',
               'twitter_url'=>'https://twitter.com/dubaifashionweek',
               'youtube_url'=>'https://www.youtube.com/c/ArabfashionweekOrg',
               'linkedin_url'=>'https://www.linkedin.com/company/arab-fashion-week/',
               'bg_color'=>'#0406f0',
               'footer_banner'=>[
               ['image'=>'uploads/cms/DFW-LOGO-WEB.png','url'=>'https://arabfashioncouncil.com/']
               ]
            ]
        ]);

         Cms::create([
            'page' => 'messages',
            'data' => [
                'booking_message' => "We sincerely appreciate your registration for Dubai Fashion Week, SS24, taking place from October 8th to 15th, 2023. This prestigious event is founded by Dubai Design District (d3) and the Arab Fashion Council.  \n\n Your application has been received by our protocol team and will undergo review within the next five working days. \n\n Kindly anticipate emails from 'ohvu.io.' We recommend regular email monitoring, including your spam/junk folder, and staying vigilant on WhatsApp for updates. \n\n Thank you for choosing to attend Dubai Fashion Week SS24. We look forward to welcoming you.",
                'designer_booking_message' => "We sincerely appreciate your registration for Dubai Fashion Week, SS24, taking place from October 8th to 15th, 2023. This prestigious event is founded by Dubai Design District (d3) and the Arab Fashion Council.  \n\n Your application has been received by our protocol team and will undergo review within the next five working days. \n\n Kindly anticipate emails from 'ohvu.io.' We recommend regular email monitoring, including your spam/junk folder, and staying vigilant on WhatsApp for updates. \n\n To register with additional designers, utilize 'dfw.ohvu.io.' \n\n Thank you for choosing to attend Dubai Fashion Week SS24. We look forward to welcoming you.",
            ]
        ]);
         Cms::create([
            'page' => 'email-templates',
            'data' => [
             'temp1'=>[
             'bg_image'=>'uploads/templates/temp1bg.jpg',
             'footer_image'=>'uploads/templates/temp1footer.jpg',
             'text1'=>'VIP',
             'text2'=>'NAME:',
             'text3'=>'BOOKED SHOWS',
             ],
             'temp2'=>[
             'bg_image'=>'uploads/templates/mrskeepaticketbg.png',
             'text1'=>'CONFIRMATION TICKET',
             'text2'=>'celeberating the official launch of<br> mrs.keep studio',
             'text3'=>'monday february 5th <br>1:00PM <br>sothebys gallery, difc',
             'text4'=>'please present this ticket <br> at check-in desk',
             'text5'=>'Dubai fashion week',
             ],
             'temp3'=>[
             'image_one'=>'uploads/templates/venuecard3.png',
             'image_two'=>'uploads/templates/logodubf.png',
             'text1'=>'CONFIRMATION TICKET',
             'text2'=>'META & THE ARAB FASHION COUNCIL',
             'text3'=>'ARE LOOKING FORWARD TO WELCOMING YOU AT THE',
             'text4'=>'DURING',
             'text5'=>'13th october 2023 | 10.00 am - 12.00 am',
             'text6'=>'main dfw venue blog 11, <br> dubai design ditrict (d3)',
             ],
             'temp4'=>[
             'bg_image'=>'uploads/templates/zeenaZaki.png',
             ],
            ]
        ]);
    }
}
