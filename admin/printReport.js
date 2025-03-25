// Print Report (Only Bar Graph)
function printReport() {
    let originalCanvas = document.getElementById('sitInChart'); // Get the original chart canvas
    let chartData = sitInChart.data; // Retrieve the existing chart data

    // Open a new print window
    let printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>Print Report</title>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
            <style>
                body {
                    font-family: Arial, sans-serif;
                    text-align: center;
                }
                .bar-graph {
                    width: 80%;
                    margin: auto;
                }
            </style>
        </head>
        <body>
            <h3>Sit-ins by Subject</h3>
            <div class="bar-graph">
                <canvas id="printChart"></canvas>
            </div>
            <script>
                window.onload = function() {
                    let ctx = document.getElementById('printChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: ${JSON.stringify(chartData)}, // Use the existing chart data
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    window.print();
                };
            <\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}
