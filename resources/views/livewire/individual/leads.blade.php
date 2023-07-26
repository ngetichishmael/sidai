<div class="m-2 mb-1 row">
    <div class="ml-1 col-md-2">
        <label for="">Type</label>
        <select id="typeSelect" class="form-control">
            <option value="sales" selected>Sales</option>
            <option value="leads">Leads</option>
            <option value="visit">Visits</option>
            <option value="order">Orders</option>
        </select>
    </div>
    <div class="col-12">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let salesChartInstance = null;

        // Function to fetch data and create the chart
        function fetchDataAndCreateChart(type) {
            fetch(`api/get/targets/${type}`)
                .then(response => response.json())
                .then(data => {
                    // Destroy the previous chart instance if it exists
                    if (salesChartInstance) {
                        salesChartInstance.destroy();
                    }

                    const ctx = document.getElementById('salesChart').getContext('2d');
                    salesChartInstance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                    label: data.label.concat('Target'),
                                    backgroundColor: '#636363',
                                    data: data.targets,
                                    borderWidth: 2,
                                    borderRadius: 5,
                                    borderSkipped: false
                                },
                                {
                                    label: 'Achieved',
                                    backgroundColor: data.color,
                                    data: data.achieved,
                                    borderWidth: 2,
                                    borderRadius: 5,
                                    borderSkipped: false
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        // Fetch initial data and create the chart when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            const initialType = document.getElementById('typeSelect').value;
            fetchDataAndCreateChart(initialType);
        });

        // Event listener to fetch data and update the chart when the type is changed
        document.getElementById('typeSelect').addEventListener('change', function() {
            const selectedType = this.value;
            fetchDataAndCreateChart(selectedType);
        });
    </script>
@endsection
