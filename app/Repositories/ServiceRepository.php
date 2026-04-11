<?php

namespace App\Repositories;

use App\Http\Requests\ServiceRequest;
use App\Models\Additional;
use App\Models\Service;
use App\Models\Variant;

class ServiceRepository extends Repository
{
    private $path = 'images/services/';

    public function model()
    {
        return new Service();
    }

    public function getAll($isLatest = false)
    {
        $services = $this->query();
        if ($isLatest) {
            $services->latest('id');
        }

        return $services->get();
    }
    public function getAllAdditional()
    {
        return Service::where('is_active', true)->orderByDesc('created_at')->take(4)->with('additionalServices')->get();
    }
    public function getProductBySlug($serviceSlug)
    {
        $serviceId = $this->model()->where('slug', $serviceSlug)->value('id');
        return $this->model()->where('is_active', 1)
            ->whereHas('variants', function ($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })
            ->with(['variants' => function ($q) use ($serviceId) {
                $q->where('service_id', $serviceId)->with('products');
            }])
            ->get();
    }

    public function getByStore($storeSlug){
        return $this->model()->where('is_active', 1)
        ->whereHas('stores', function ($q) use ($storeSlug) {
            $q->where('stores.slug', $storeSlug);
        })->get();
    }

    public function getActiveServices()
    {
        return $this->query()->isActive()->get();
    }

    public function storeByRequest(ServiceRequest $request): Service
    {
        $thumbnail = (new MediaRepository())->storeByRequest(
            $request->image,
            $this->path,
            'this image for service thumbnail',
            'image'
        );

        return $this->create([
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'description' => $request->description,
            'description_bn' => $request->description_bn,
            'thumbnail_id' => $thumbnail->id,
        ]);
    }

    public function updateByRequest(ServiceRequest $request, Service $service): Service
    {

        if ($request->hasFile('image')) {
            (new MediaRepository())->updateOrCreateByRequest(
                $request->image,
                $this->path,
                'image',
                $service->thumbnail
            );
        }

        $this->update($service, [
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'description' => $request->description,
            'description_bn' => $request->description_bn,
        ]);

        return $service;
    }

    public function updateStatusById(Service $service): Service
    {
        $service->update([
            'is_active' => ! $service->is_active,
        ]);

        return $service;
    }

    public function findOrFailById($serviceId): Service
    {
        $service = $this->model()->findOrFail($serviceId);

        return $service;
    }
}
