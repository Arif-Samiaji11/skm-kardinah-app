<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Detail SKM #{{ $detail->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-4">
                            <div><span class="text-xs text-gray-500">ID</span><div class="font-semibold">{{ $parent->id }}</div></div>
                            <div><span class="text-xs text-gray-500">Nama Ruangan</span><div class="font-semibold">{{ $parent->nama_ruangan }}</div></div>
                            <div><span class="text-xs text-gray-500">Bulan</span><div class="font-semibold">{{ $parent->bulan }}</div></div>
                            <div><span class="text-xs text-gray-500">Tahun</span><div class="font-semibold">{{ $parent->tahun }}</div></div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('ruangan-periode.detail.update', [$parent->id, $detail->id]) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            @for ($i=1; $i<=9; $i++)
                                @php $field = 'u'.$i; @endphp
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">U{{ $i }} (1-5)</label>
                                    <input type="number" min="1" max="5" name="{{ $field }}"
                                           value="{{ old($field, $detail->$field) }}"
                                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    @error($field) <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            @endfor
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('ruangan-periode.detail.index', $parent->id) }}"
                               class="rounded-lg border px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Kembali
                            </a>

                            <button type="submit"
                                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                Update
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
