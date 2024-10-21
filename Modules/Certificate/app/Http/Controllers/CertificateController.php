<?php

namespace Modules\Certificate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Modules\Auth\Models\Account;
use Modules\Certificate\Http\Requests\CreateCertificateRequest;
use Modules\Certificate\Http\Requests\UpdateCertificateRequest;
use Modules\Certificate\Models\Certificate;
use Modules\Certificate\Transformers\CertificateResource;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Account $account)
    {
        $data = Certificate::with('image')
            ->where('account_id', $account->id)
            ->get();

        return CertificateResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCertificateRequest $request)
    {
        $certificate = $request->account->certificates()->create(Arr::except($request->validated(), ['image']));

        $media = Media::find($request->post('image'));

        $media->move($certificate, MediaCollectionType::CERTIFICATE_IMAGE->value);

        return CertificateResource::make($certificate->loadMissing('image'));
    }

    /**
     * Show the specified resource.
     */
    public function show(Account $account, Certificate $certificate)
    {
        return CertificateResource::make($certificate->loadMissing('image'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCertificateRequest $request, Certificate $certificate)
    {
        $certificate->update(Arr::except($request->validated(), ['image']));

        if ($request->has('image')) {
            $media = Media::find($request->post('image'));

            $media->move($certificate, MediaCollectionType::CERTIFICATE_IMAGE->value);
        }

        return CertificateResource::make($certificate->fresh()->load('image'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return response(['message' => 'Deleted successfully']);
    }
}
