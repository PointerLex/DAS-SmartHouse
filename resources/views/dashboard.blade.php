<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Smart house - Sistema de adquisición y distribución de datos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Últimos datos de sensores
                    </h3>
                </div>
            </div>

            {{-- Gráfico --}}
            <div class="mt-12">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Gráfico de niveles de sensores</h3>
                <canvas id="sensorChart" class="w-full h-64"></canvas>
            </div>

            {{-- Tarjetas de Datos de los Sensores --}}
            {{-- <div id="cards-container" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($readings as $reading)
                    <div class="sensor-card p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ ucfirst($reading->sensor_type) }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">
                            <strong>Valor:</strong> {{ $reading->value }}
                        </p>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">
                            <strong>Estado:</strong> {{ $reading->status }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
                            Registrado el: {{ $reading->created_at }}
                        </p>
                    </div>
                @endforeach
            </div> --}}

            <div class="mt-2">
                {{ $readings->links() }}
            </div>


        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('sensorChart').getContext('2d');
        const sensorChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($readings->pluck('created_at')->map(fn($time) => $time->format('H:i:s'))),
                datasets: [{
                        label: 'Niveles de gas',
                        data: @json($readings->where('sensor_type', 'gas')->pluck('value')),
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.1,
                    },
                    {
                        label: 'Intensidad de luz',
                        data: @json($readings->where('sensor_type', 'luz')->pluck('value')),
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.1,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Hora'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Valor'
                        }
                    }
                }
            }
        });
    </script>

    <script>
        window.Echo.channel('sensor-readings')
            .listen('SensorReadingUpdated', (event) => {
                console.log('Evento recibido:', event);

                // Asegúrate de que estas propiedades existan
                const reading = event.reading || event;

                const card = `
        <div class="sensor-card p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                ${reading.sensor_type.charAt(0).toUpperCase() + reading.sensor_type.slice(1)}
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                <strong>Valor:</strong> ${reading.value}
            </p>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                <strong>Estado:</strong> ${reading.status}
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
                Registrado el: ${reading.last_seen}
            </p>
        </div>
    `;

                // Insertar la tarjeta en el contenedor
                const cardsContainer = document.getElementById('cards-container');
                cardsContainer.insertAdjacentHTML('afterbegin', card);
            });
    </script>

    <script>
        const eventSource = new EventSource('/stream-disconnections');

        eventSource.onmessage = function(event) {
            const data = JSON.parse(event.data);
            const sensors = data.disconnected_sensors;

            if (sensors && sensors.length > 0) {
                let sensorList = sensors.map(sensor =>
                    `<li>${sensor.sensor_type} (última conexión: ${sensor.last_seen})</li>`).join('');

                Swal.fire({
                    icon: 'error',
                    title: '¡Sensores desconectados!',
                    html: `<p>Se detectaron los siguientes sensores desconectados:</p><ul>${sensorList}</ul>`,
                    confirmButtonText: 'Entendido'
                });
            }
        };
    </script>




</x-app-layout>
