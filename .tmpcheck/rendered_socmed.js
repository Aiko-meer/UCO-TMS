
         document.addEventListener("DOMContentLoaded", function() {
             // Helper function to create charts cleanly
             function createProgressChart(elementId, percentage, color) {
                 var ctx = document.getElementById(elementId).getContext('2d');
                 new Chart(ctx, {
                     type: 'doughnut',
                     data: {
                         datasets: [{
                             data: [percentage, 100 - percentage],
                             backgroundColor: [color, '#e9ecef'],
                             borderWidth: 0
                         }]
                     },
                     options: {
                         cutoutPercentage: 80,
                         tooltips: {
                             enabled: false
                         },
                         responsive: false,
                         maintainAspectRatio: false
                     }
                 });
             }

             // Initialize each chart with its specific color
             createProgressChart('dynamicInProgressChart', 0, '#ffc107'); // Accent / Yellow
             createProgressChart('dynamicPostedChart', 0, '#007bff'); // Primary / Blue
             createProgressChart('dynamicApprovalChart', 100, '#28a745'); // Success / Green
         });
     