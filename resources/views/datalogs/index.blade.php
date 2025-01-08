<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registros de sensores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Historial de datos de sensores
                    </h3>
                </div>

                <table class="min-w-full mt-4 table-auto">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-white">
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Tipo de sensor</th>
                            <th class="px-4 py-2">Valor</th>
                            <th class="px-4 py-2">Estado</th>
                            <th class="px-4 py-2">Registrado en</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($readings as $index => $reading)
                        <tr class="text-center text-slate-300">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ ucfirst($reading->sensor_type) }}</td>
                            <td class="px-4 py-2">{{ $reading->value }}</td>
                            <td class="px-4 py-2">{{ $reading->status }}</td>
                            <td class="px-4 py-2">{{ $reading->created_at->format('d/m/y [H:i:s]') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-2">
                    {{ $readings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
