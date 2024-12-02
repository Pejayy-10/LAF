<div>
    <div id="text-overview">
        <h2 class="text-danger">Analytics Overview</h2>
    </div>
    
    <canvas id="analyticsChart"></canvas>
</div>

<script>
    // Load analytics chart
    $(document).ready(function () {
        loadChart();
    });

    function loadChart() {
        const ctx = document.getElementById('analyticsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [
                    {
                        label: 'Lost Items',
                        data: [5, 15, 10, 20, 30,5],
                        backgroundColor: '#dc3545',
                    },
                    {
                        label: 'Found Items',
                        data: [10, 10, 15, 5,5,10],
                        backgroundColor: '#0dcaf0',
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Lost vs. Found Items',
                    },
                },
            },
        });
    }
</script>
