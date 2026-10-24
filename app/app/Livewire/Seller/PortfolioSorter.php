<?php

namespace App\Livewire\Seller;

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PortfolioSorter extends Component {
    #[Computed]
    public function designs(): Collection {
        return Design::query()
            ->where('user_id', Auth::id())
            ->withCount('products')
            ->orderBy('sort')
            ->orderByDesc('id')
            ->get();
    }

    #[Computed]
    public function customProducts(): Collection {
        return $this->customProductsQuery()
            ->with('type', 'color', 'position', 'design_with_trashed')
            ->orderBy('sort')
            ->orderByDesc('id')
            ->get();
    }

    private function customProductsQuery(): Builder {
        return Product::query()
            ->where('user_id', Auth::id())
            ->where(function (Builder $query) {
                $query->where('design_id', false)
                    ->orWhereIn('design_id', function ($subQuery) {
                        $subQuery->select('id')
                            ->from('designs')
                            ->where('custom', true);
                    });
            });
    }

    public function saveOrder(string $type, array $ids): void {
        validator(
            ['type' => $type, 'ids' => $ids],
            [
                'type' => ['required', 'in:design,product'],
                'ids' => ['required', 'array', 'min:1'],
                'ids.*' => ['required', 'integer', 'distinct', 'min:1'],
            ],
        )->validate();

        $query = $type === 'design'
            ? Design::query()->where('user_id', Auth::id())
            : $this->customProductsQuery();

        DB::transaction(function () use ($query, $ids) {
            $allowedIds = (clone $query)
                ->lockForUpdate()
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->sort()
                ->values()
                ->all();

            $submittedIds = collect($ids)
                ->map(fn($id) => (int) $id)
                ->sort()
                ->values()
                ->all();

            if ($submittedIds !== $allowedIds) {
                throw ValidationException::withMessages([
                    'ids' => 'Invalid portfolio ordering.',
                ]);
            }

            foreach ($ids as $index => $id) {
                (clone $query)
                    ->whereKey($id)
                    ->update(['sort' => $index + 1]);
            }
        });
    }

    public function render() {
        return view('livewire.seller.portfolio-sorter', [
            'designs' => $this->designs,
            'customProducts' => $this->customProducts,
        ]);
    }
}
