<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Offres
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-2 text-right">
                        <x-primary-link href="{{ route('offers.create') }}">Ajouter</x-primary-link>
                    </div>
                    <!-- Filter form -->
                    <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
                        <div class="flex gap-3 flex-row sm:items-end">
                            <div class="flex-1">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    value="{{ request('name') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>
                            <div class="flex-1">
                                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                                <input
                                    type="text"
                                    name="slug"
                                    id="slug"
                                    value="{{ request('slug') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>
                            <div class="pt-4">
                                <x-secondary-button>Filtrer</x-secondary-button>
                            </div>
                        </div>
                        <input type="hidden" name="state" value="{{ request('state') }}">
                    </form>

                    <div class="flex h-8">
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('dashboard')" :active="request('state') == null">
                                Tous
                            </x-nav-link>
                        </div>
                        @foreach(\App\Enums\OfferState::cases() as $offerState)
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-nav-link :href="route('dashboard', ['state' => $offerState->value])" :active="request('state') == $offerState->value">
                                    {{ $offerState->label() }}
                                </x-nav-link>
                            </div>
                        @endforeach
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 w-full">
                            <thead class="border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Image</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Slug</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">État</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($offers as $offer)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                            <a href="{{ route('offers.show', $offer) }}">{{ $offer->id }}</a>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <img src="{{ asset('storage/'.$offer->image) }}" alt="Image of {{ $offer->name }}" class="h-12 w-12 object-cover rounded-md border border-gray-200 dark:border-gray-700">
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('offers.show', $offer) }}">{{ $offer->name }}</a>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $offer->slug }}</td>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300 max-w-md">
                                            <div class="line-clamp-2">{{ $offer->description }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 px-2 py-1">{{ $offer->state->label() }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-right whitespace-nowrap">
                                            <x-primary-link href="{{ route('offers.edit', $offer) }}">Modifier</x-primary-link>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
