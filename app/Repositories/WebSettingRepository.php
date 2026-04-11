<?php
namespace App\Repositories;

use App\Models\WebSetting;
use Illuminate\Support\Facades\Storage;

class WebSettingRepository extends Repository
{
    public function model()
    {
        return WebSetting::class;
    }

    public function getType($type){
        return self::query()->where('key', $type)->first();
    }

    public function processHeader($request, $existingValue){
        $existing = json_decode($existingValue->value ?? '{}', true);

        $imageGroups = self::handleImageArray($request->trusted_client_image_group ?? [],
            $existing['trusted_client_image_group'] ?? [],'header/trusted_client_image_group','img'
        );

        $headerImagePath = self::handleImage($request->file('header_img'),$existing['header_img'] ?? null,'headers/img');

        $jsonData = [
            'trusted_client_image_group' => $imageGroups,
            'header_img'        => $headerImagePath,
            'title'       => $request->input('title', $existing['title'] ?? ''),
            'description' => $request->input('description', $existing['description'] ?? ''),
        ];

        return $jsonData;
    }

    public function processPremiumService($request, $existingValue){
        $existing = json_decode($existingValue->value ?? '{}', true);
        $jsonData = [
            'title'       => $request->input('title', $existing['title'] ?? ''),
            'sub_title' => $request->input('sub_title', $existing['sub_title'] ?? ''),
        ];
        return $jsonData;
    }
    public function processExperienceSection($request, $existingValue){
        $existing = json_decode($existingValue->value ?? '{}', true);
        $jsonData = [
            'title'       => $request->input('title', $existing['title'] ?? ''),
            'sub_title' => $request->input('sub_title', $existing['sub_title'] ?? ''),
        ];
        return $jsonData;
     }

    public function processChooseSection($request, $existingValue){
        $existing = json_decode($existingValue->value ?? '{}', true);

        $chooseThumbPath = self::handleImage(
            $request->file('choose_thumb'),
            $existing['choose_thumb'] ?? null,
            'choose/thumb'
        );

        $listItems = [];

        if ($request->has('list_item')) {
            $listItems = $request->has('list_item')
                ? self::handleImageArray($request->list_item, $existing['list_item'] ?? [], 'choose/list_item', 'thumb')
                : ($existing['list_item'] ?? []);
        } else {
            $listItems = $existing['list_item'] ?? [];
        }

        return [
            'choose_thumb' => $chooseThumbPath,
            'title'        => $request->input('title', $existing['title'] ?? ''),
            'description'  => $request->input('description', $existing['description'] ?? ''),
            'list_item'    => $listItems
        ];
    }

    public function processHowItWorkSection($request, $existingValue){
        $existing = json_decode($existingValue->value ?? '{}', true);

        $requestFeatureCards = $request->has('work_steps')
        ? array_values($request->work_steps)
        : [];

        foreach ($requestFeatureCards as $index => &$card) {
            $card['number'] = $requestFeatureCards[$index]['number'] ?? ($existing['work_steps'][$index]['number'] ?? '');
            $card['title'] = $requestFeatureCards[$index]['title'] ?? ($existing['work_steps'][$index]['title'] ?? '');
            $card['subtitle'] = $requestFeatureCards[$index]['sub_title'] ?? ($existing['work_steps'][$index]['sub_title'] ?? '');
        }

        $rightSideImage = self::handleImage($request->file('right_side_img') ?? null, $existing['right_side_img'] ?? null, 'works/right_side_img');

        return [
            'title'            => $request->input('title', $existing['title'] ?? ''),
            'right_side_img' => $rightSideImage,
            'work_steps' => $requestFeatureCards,

        ];
    }

    public function processBuildSection($request, $existingValue)
    {
        $existing = json_decode($existingValue->value ?? '{}', true);

        $sampleCards = !empty($request->has('sample'))
            ? self::handleImageArrayWithInfo($request->sample, $existing['sample'] ?? [], 'sample/icons', 'icon')
            : ($existing['sample'] ?? []);

        return [
            'title'            => $request->input('title', $existing['title'] ?? ''),
            'sub_title'      => $request->input('sub_title', $existing['sub_title'] ?? ''),
            'sample'           => $sampleCards
        ];
    }

    public function processPromiseSection($request, $existingValue)
    {
        $existing = json_decode($existingValue->value ?? '{}', true);

        $backgroundPath = self::handleImage( $request->file('background_image') ?? null, $existing['background_image'] ?? null,'promise/background');
        $sideImagePath = self::handleImage( $request->file('side_image') ?? null, $existing['side_image'] ?? null,'promise/image');

        return [
            'title'            => $request->input('title', $existing['title'] ?? ''),
            'sub_title'        => $request->input('sub_title', $existing['sub_title'] ?? ''),
            'background_image' => $backgroundPath,
            'side_image'       => $sideImagePath,
        ];
    }

    public function processNetworkSection($request, $existingValue)
    {
        $existing = json_decode($existingValue->value ?? '{}', true);


            $requestListsCards = $request->has('lists')? array_values($request->lists): [];

            foreach ($requestListsCards as $index => &$card) {
                $card['list'] = $requestListsCards[$index]['list'] ?? ($existing['lists'][$index]['list'] ?? '');
            }

        $facilityCards = !empty($request->has('facilities'))
            ? self::handleImageArrayWithInfo($request->facilities, $existing['facilities'] ?? [], 'facilities/icon', 'icon')
            : ($existing['facilities'] ?? []);

        return [
            'title'            => $request->input('title', $existing['title'] ?? ''),
            'description'      => $request->input('description', $existing['description'] ?? ''),
            'lists'            => $requestListsCards,
            'facilities'       => $facilityCards
        ];
    }
    public function processTakeWithSection($request, $existingValue)
    {
        $existing = json_decode($existingValue->value ?? '{}', true);
        $imageGroups = self::handleImageArray($request->image_group ?? [],
            $existing['image_group'] ?? [],'take-with/image_group','img'
        );

        $requestButtonCards = $request->has('button_group')? array_values($request->button_group): [];

        foreach ($requestButtonCards as $index => &$card) {
            $card['link'] = $requestButtonCards[$index]['link'] ?? ($existing['button_group'][$index]['link'] ?? '');
            $card['name'] = $requestButtonCards[$index]['name'] ?? ($existing['button_group'][$index]['name'] ?? '');
        }

        $infosCards = !empty($request->has('infos'))
            ? self::handleImageArrayWithInfo($request->infos, $existing['infos'] ?? [], 'infos/icon', 'icon')
            : ($existing['infos'] ?? []);

        $takeInfo = [];
        if($request->take_info){
            $takeInfo[0]['title'] = $request->take_info['title'];
            $takeInfo[0]['sub_title'] = $request->take_info['sub_title'];
            if( isset($request->take_info['icon'])  && $request->take_info['icon']){

                $takeInfo[0]['icon'] = self::handleImage($request->take_info['icon'],$existing['take_info'][0]['icon'] ?? null,'take_info/img');
            }else{

                $takeInfo[0]['icon'] = $existing['take_info'][0]['icon'];
            }
        }


        $rightSideImagePath = self::handleImage($request->file('right_side_image'),$existing['right_side_image'] ?? null,'right_side/img');

        return [
            'title'             => $request->input('title', $existing['title'] ?? ''),
            'sub_title'         => $request->input('sub_title', $existing['sub_title'] ?? ''),
            'button_group'      => $requestButtonCards,
            'infos'             => $infosCards,
            'take_info'         => $takeInfo,
            'right_side_image'  => $rightSideImagePath,
            'image_group'       => $imageGroups,
        ];
    }

    public function processFooterSection( $request, $existingValue)
    {
        $existing = json_decode($existingValue->value ?? '{}', true);

        $footerImagePath = self::handleImage($request->file('footer_logo'),$existing['footer_logo'] ?? null,'footer/img');
        $footerBackgroundImagePath = self::handleImage($request->file('footer_background'),$existing['footer_background'] ?? null,'footer/img');
        $followUs = !empty($request->has('follow_us'))
            ? self::handleImageArrayWithInfo($request->follow_us, $existing['follow_us'] ?? [], 'follow/icon', 'icon')
            : ($existing['follow_us'] ?? []);

        $contactInfo['address'] = $request->contact_us['address'];
        $contactInfo['phone_number'] = $request->contact_us['phone_number'];

        return [
            'footer_title'             => $request->input('footer_title', $existing['footer_title'] ?? ''),
            'footer_left_side_text'    => $request->input('footer_left_side_text', $existing['footer_left_side_text'] ?? ''),
            'footer_right_side_text'   => $request->input('footer_right_side_text', $existing['footer_right_side_text'] ?? ''),
            'footer_logo'              => $footerImagePath,
            'footer_background'        => $footerBackgroundImagePath,
            'contact_us'               => $contactInfo,
            'follow_us'                => $followUs,
        ];

    }

    public function processGetStartedSection($request, $existingValue){
        $existing = json_decode($existingValue->value ?? '{}', true);
        $jsonData = [
            'title'       => $request->input('title', $existing['title'] ?? ''),
            'sub_title' => $request->input('sub_title', $existing['sub_title'] ?? ''),
        ];
        return $jsonData;
     }

    public function handleImage($newFile, $oldFilePath, $directory)
    {
        if ($newFile instanceof \Illuminate\Http\UploadedFile) {
            if (!empty($oldFilePath) && Storage::disk('public')->exists($oldFilePath)) {
                Storage::disk('public')->delete($oldFilePath);
            }
            return $newFile->store($directory, 'public');
        }
        return $oldFilePath;
    }

    public function handleImageArray($requestArray, $existingArray, $directory, $fieldName = 'img')
    {
        $result = [];

        foreach ($requestArray as $item) {

            $newFile = $item[$fieldName] ?? null;
            $oldFile = $item['existing_img'] ?? null;

            if ($newFile instanceof \Illuminate\Http\UploadedFile) {
                $path = self::handleImage($newFile, $oldFile, $directory);
            }

            else {
                $path = $oldFile;
            }

            $cleanItem = $item;

            $cleanItem[$fieldName] = $path;

            unset($cleanItem['existing_img']);

            $result[] = $cleanItem;
        }

        return $result;
    }

//     public function handleImageArray($requestArray, $existingArray, $directory, $fieldName = 'img')
//     {
//         // $result = $existingArray;
// $result = [];

//         foreach ($requestArray as $index => $item) {

//             $newFile = $item[$fieldName] ?? null;
//             $oldFile = $existingArray[$index][$fieldName] ?? null;
//             $path = self::handleImage($newFile, $oldFile, $directory);

//             $result[$index] = $existingArray[$index] ?? [];
//             foreach ($item as $key => $value) {
//                 $result[$index][$key] = $value;
//             }

//             $result[$index][$fieldName] = $path;
//         }

//         return array_values($result);
//     }


    public function handleImageArrayWithInfo($requestArray, $existingArray, $directory, $fieldName = 'img')
    {

        $result = [];

        foreach ($requestArray as $index => $item) {

            $newFile = $item[$fieldName] ?? null;
            $oldFile = $existingArray[$index][$fieldName] ?? null;
            if($newFile == null){
                $index ++;
            }

            $path = $this->handleImage($newFile, $oldFile, $directory);

            // Ensure index exists
            if (!isset($result[$index])) {
                $result[$index] = [];
            }

            // Merge other fields
            foreach ($item as $key => $value) {
                if ($key !== $fieldName) {
                    $result[$index][$key] = $value;
                }
            }

            // Always set image path
            $result[$index][$fieldName] = $path;
            $index++;
            $result = array_values($result);
        }

        return $result;
    }

}

