<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Offre — {{ $offer->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-start gap-6 mb-6">
                        <div class="w-32 h-32 flex-shrink-0 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden bg-gray-50 dark:bg-gray-700">
                            @if($offer->image)
                                <img src="{{ asset('storage/'.$offer->image) }}" alt="Image {{ $offer->name }}" style="width: 200px">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">—</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <h3 class="text-2xl font-semibold">{{ $offer->name }}</h3>
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    {{ $offer->state->label() }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Slug: <span class="font-mono">{{ $offer->slug }}</span></p>
                            @if($offer->description)
                                <p class="mt-3 leading-relaxed">{{ $offer->description }}</p>
                            @endif

                            <div class="mt-4 flex gap-2">
                                <x-primary-link href="{{ route('offers.edit', $offer->id) }}">Modifier l'offre</x-primary-link>
                                <x-secondary-link href="{{ route('offers.products.index', $offer->id) }}">Gérer les produits</x-secondary-link>
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">Retour</a>
                            </div>
                        </div>
                    </div>

                    <h4 class="text-lg font-semibold mb-3">Produits liés</h4>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 w-full">
                            <thead class="border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Image</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">SKU</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prix</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">État</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($offer->products as $product)
                                    <tr>
                                        <td class="px-4 py-3">{{ $product->id }}</td>
                                        <td class="px-4 py-3">
                                            @if($product->image)
                                                <img src="{{ asset('storage/'.$product->image) }}" alt="Image {{ $product->name }}" class="h-12 w-12 object-cover rounded-md border border-gray-200 dark:border-gray-700">
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                                        <td class="px-4 py-3">{{ $product->sku }}</td>
                                        <td class="px-4 py-3">{{ number_format((float)$product->price, 2, ',', ' ') }} €</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                {{ $product->state->label() }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun produit lié à cette offre.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
