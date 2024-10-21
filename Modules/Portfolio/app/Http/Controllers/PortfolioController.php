<?php

namespace Modules\Portfolio\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Modules\Auth\Models\Account;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;
use Modules\Portfolio\Http\Requests\CreatePortfolioRequest;
use Modules\Portfolio\Http\Requests\UpdatePortfolioRequest;
use Modules\Portfolio\Models\Portfolio;
use Modules\Portfolio\Transformers\PortfolioResource;

class PortfolioController extends Controller
{
    public function index(Account $account)
    {
        $data = Portfolio::with('images')
            ->where('account_id', $account->id)
            ->get();

        return PortfolioResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePortfolioRequest $request)
    {
        $portfolio = $request->account->portfolios()->create(Arr::except($request->validated(), ['images']));

        Media::whereIn('id', $request->post('images'))
            ->get()
            ->each(function (Media $media) use ($portfolio) {
                $media->move($portfolio, MediaCollectionType::PORTFOLIO_IMAGES->value);
            });

        return PortfolioResource::make($portfolio->loadMissing('images'));
    }

    public function show(Account $account, Portfolio $portfolio)
    {
        return PortfolioResource::make($portfolio->loadMissing('images'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePortfolioRequest $request, Portfolio $portfolio)
    {
        $portfolio->update(Arr::except($request->validated(), ['images']));

        if ($request->has('images')) {
            $medias = Media::whereIn('id', $request->post('images', []))
                ->get();

            $updatedImageIds = $medias
                ->filter(fn (Media $media) => $media->collection_name === MediaCollectionType::PORTFOLIO_IMAGES->value)
                ->map(fn (Media $media) => $media->id)
                ->toArray();

            $medias->filter(fn (Media $media) => $media->collection_name !== MediaCollectionType::PORTFOLIO_IMAGES->value)
                ->each(function (Media $media) use (&$updatedImageIds, $portfolio) {
                    $media = $media->move($portfolio, MediaCollectionType::PORTFOLIO_IMAGES->value);
                    $updatedImageIds[] = $media->fresh()->id;
                });

            Media::whereNotIn('id', $updatedImageIds)
                ->where('model_id', $portfolio->id)
                ->where('model_type', get_class($portfolio))
                ->delete();
        }

        return PortfolioResource::make($portfolio->fresh()->load('images'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portfolio $portfolio)
    {
        $portfolio->delete();

        return response(['message' => 'Deleted successfully']);
    }
}
