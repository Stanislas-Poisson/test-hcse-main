<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Offres
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Créer une offre
                            </h2>
                        </header>

                        <form method="post" action="{{ route('offers.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf

                            <div>
                                <x-input-label for="name" value="Nom" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="slug" value="Slug" />
                                <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                            </div>

                            <div>
                                <x-input-label for="image" value="Image" />
                                <x-file-input id="image" name="image" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('image')" />
                            </div>

                            <div>
                                <x-input-label for="description" value="Description" />
                                <x-text-area id="description" name="description" class="mt-1 block w-full" :value="old('description')" />
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div>
                                <x-input-label for="state" value="État" />
                                <x-select id="state" name="state" class="mt-1 block w-full" required>
                                    <option value="draft" @selected(old('state') == 'draft')>Brouillon</option>
                                    <option value="published" @selected(old('state') == 'published')>Publié</option>
                                    <option value="hidden" @selected(old('state') == 'hidden')>Masquée</option>
                                </x-select>
                                <x-input-error class="mt-2" :messages="$errors->get('state')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>Ajouter</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
