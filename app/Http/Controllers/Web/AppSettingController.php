<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Repositories\MediaRepository;
use Exception;
use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    private $path = 'images/webs/';

    public function index()
    {
        $appSetting = AppSetting::first();

        $zones = [];
        $timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $zones[$key]['zone'] = $zone;
            $zones[$key]['diff_from_GMT'] = 'UTC/GMT '.date('P', $timestamp);
        }

        return view('web-setting', compact('appSetting', 'zones'));
    }

    public function update(Request $request, AppSetting $appSetting)
    {
        $request->validate([
        'business_system' => 'required|in:subscription,commission',
    ], [
        'business_system.required' => 'Must select a business system',
    ]);
        $appSettingLogo = $this->WebLogoUpdate($request, $appSetting);
        $appSettingFavicon = $this->WebFaviconUpdate($request, $appSetting);
        $signature = $this->SignatureUpdate($request, $appSetting);
        AppSetting::updateOrCreate(
            [
                'id' => $appSetting ? $appSetting->id : 0,
            ],
            [
                'name' => $request->name,
                'title' => $request->title,
                'logo' => $appSettingLogo ? $appSettingLogo->id : null,
                'fav_icon' => $appSettingFavicon ? $appSettingFavicon->id : null,
                'signature_id' => $signature ? $signature->id : null,
                'city' => $request->city,
                'address' => $request->address,
                'road' => $request->road,
                'area' => $request->area,
                'mobile' => $request->mobile,
                'currency' => $request->currency,
                'currency_position' => $request->currency_position,
                'pick_to_delivery_gap' => $request->pick_to_delivery_gap,
                'direction' => $request->direction,
                'business_based_on' => $request->business_system,
                'is_commission_due'  => $request->boolean('is_commission_due') ? 1 : 0,
            ]
        );

        if (config('app.timezone') != $request->timezone) {
            $this->setEnv('APP_TIMEZONE', $request->timezone);
        }

        return back()->with('success', 'Update Successfully');
    }

    private function WebLogoUpdate($request, $appSetting)
    {
        $thumbnail = $appSetting->websiteLogo;
        if ($request->hasFile('logo') && $thumbnail == null) {
            $thumbnail = (new MediaRepository())->storeByRequest(
                $request->logo,
                $this->path,
                'website logo',
                'image'
            );
        }

        if ($request->hasFile('logo') && $thumbnail) {
            $thumbnail = (new MediaRepository())->updateOrCreateByRequest(
                $request->logo,
                $this->path,
                'image',
                $thumbnail
            );
        }

        return $thumbnail;
    }

    private function WebFaviconUpdate($request, $appSetting)
    {
        $thumbnail = $appSetting->websiteFavicon;
        if ($request->hasFile('fav_icon') && $thumbnail == null) {
            $thumbnail = (new MediaRepository())->storeByRequest(
                $request->fav_icon,
                $this->path,
                'website favicon',
                'image'
            );
        }

        if ($request->hasFile('fav_icon') && $thumbnail) {
            $thumbnail = (new MediaRepository())->updateOrCreateByRequest(
                $request->fav_icon,
                $this->path,
                'image',
                $thumbnail
            );
        }

        return $thumbnail;
    }

    private function SignatureUpdate($request, $appSetting)
    {
        $thumbnail = $appSetting->signature;
        if ($request->hasFile('signature') && $thumbnail == null) {
            $thumbnail = (new MediaRepository())->storeByRequest(
                $request->signature,
                $this->path,
                'website signature',
                'image'
            );
        }

        if ($request->hasFile('signature') && $thumbnail) {
            $thumbnail = (new MediaRepository())->updateOrCreateByRequest(
                $request->signature,
                $this->path,
                'image',
                $thumbnail
            );
        }

        return $thumbnail;
    }

    protected function setEnv($key, $value): bool
    {
        try {
            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);

            $keyPosition = strpos($str, "{$key}=");
            $endOfLinePosition = strpos($str, "\n", $keyPosition);
            $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

            $str = str_replace($oldLine, "{$key}={$value}", $str);

            $str = substr($str, 0, -1);
            $str .= "\n";

            file_put_contents($envFile, $str);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
