<?php

namespace App\Repositories;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;

class SettingRepository extends Repository
{
    public function model()
    {
        return Setting::class;
    }

    public function findBySlug($slug)
    {
        return $this->query()->where('slug', $slug)->first();
    }

    public function updateByRequest(SettingRequest $request, Setting $setting): Setting
    {

        if($setting->slug == 'contact-us'){
            $data = [
                'phone_no'      => $request->phone_no ?? [],
                'email'         => $request->email ?? [],
                'business'      => $request->business ?? [],
                'office_address'=> $request->office_address ?? '',
            ];

            $jsonContent = json_encode($data);
            $setting->update([
                'title' => $request->title,
                'content' => $jsonContent,
            ]);
        }else{
            $setting->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
        }


        return $setting;
    }
}
