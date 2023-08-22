<div class="card">
    <canvas id="lineChart" width="800" height="400"></canvas>

    <script type="application/javascript">
        const data = @json($graphdata);
        const labels = data.map(entry => entry.month);

        const preOrderCounts = data.map(entry => entry.preOrderCount);
        const deliveryCounts = data.map(entry => entry.deliveryCount);
        const vanSalesCounts = data.map(entry => entry.vanSalesCount);

        // Create datasets with null values for missing months
        const datasets = [
            {
                label: 'Pre Orders',
                data: preOrderCounts,
                borderColor: 'rgb(255, 69, 0)',
                borderWidth: 2,
                fill: false,
                tension: 0,
            },
            {
                label: 'Deliveries',
                data: deliveryCounts,
                borderColor: 'rgb(21, 116, 239)',
                borderWidth: 2,
                fill: false,
                tension: 0,
            },
            {
                label: 'Van Sales',
                data: vanSalesCounts,
                borderColor: 'rgb(50, 205, 50)',
                borderWidth: 2,
                fill: false,
                tension: 0,
            },
        ];

        // Adjust data for null values to make lines "float"
        for (let i = 0; i < datasets.length; i++) {
            for (let j = 0; j < datasets[i].data.length; j++) {
                if (j > 0 && datasets[i].data[j] === 0) {
                    datasets[i].data[j] = null;
                }
            }
        }

        const ctx = document.getElementById('lineChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets,
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Months',
                        },
                    },
                    y: {
                        beginAtZero: true,
                        // Calculate max value based on available data (excluding null values)
                        max: Math.ceil(
                            Math.max(
                                ...preOrderCounts.filter(value => value !== null),
                                ...deliveryCounts.filter(value => value !== null),
                                ...vanSalesCounts.filter(value => value !== null)
                            ) / 50
                        ) * 50,
                        ticks: {
                            stepSize: 50,
                        },
                        display: true,
                        title: {
                            display: true,
                            text: 'Count',
                        },
                    },
                },
            },
        });
    </script>
</div>