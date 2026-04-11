<?php

namespace App\Http\Controllers;

use App\Repositories\WebSettingRepository;
use Illuminate\Http\Request;

class WebSettingController extends Controller
{
   public function edit(){
        $webSettings =(new WebSettingRepository())->getAll();
        foreach ($webSettings as $setting) {
            $decoded = json_decode($setting->value);
            $setting->decoded_value = $decoded;
            $setting->key = $setting->key ?? null;
        }
        return view('web-settings.index', compact('webSettings'));
    }


     public function update(Request $request, $type)
    {
        $existingValue =(new WebSettingRepository())->getType($type);

        $title = $request->title;
        $title = preg_replace('/<\/p>\s*<p>/', '<br>', $title);

        $title = str_replace(['<p>', '</p>'], '', $title);

        $request['title'] = strip_tags(
            $title,
            '<br><i><em><strong><a><ul><ol><li><span>'
        );

        $footer_title = $request->footer_title;
        $footer_title = preg_replace('/<\/p>\s*<p>/', '<br>', $footer_title);

        $footer_title = str_replace(['<p>', '</p>'], '', $footer_title);

        $request['footer_title'] = strip_tags(
            $footer_title,
            '<br><i><em><strong><a><ul><ol><li><span>'
        );

        $handlers = [
            'header' => 'processHeader',
            'premium_services' => 'processPremiumService',
            'experience_services' => 'processExperienceSection',
            'how_it_works' => 'processHowItWorkSection',
            'build_on_trust' => 'processBuildSection',
            'our_promise' => 'processPromiseSection',
            'join_our_network' => 'processNetworkSection',
            'take_with_you' => 'processTakeWithSection',
            'get_started' => 'processGetStartedSection',
            'footer' => 'processFooterSection',
        ];
        $method = $handlers[$type];
        $jsonData =(new WebSettingRepository())->$method($request, $existingValue);

        $existingValue->value = json_encode($jsonData, JSON_UNESCAPED_SLASHES);
        $existingValue->save();

        return redirect()->back()->with('success', __('Web settings updated successfully.'));

    }
}
