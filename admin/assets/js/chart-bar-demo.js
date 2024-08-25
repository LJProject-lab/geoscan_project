// Function to fetch data from PHP script
function fetchDataForBarChart() {
  return fetch('functions/chart-functions/fetch-tax-permonth.php')
      .then(response => {
          if (!response.ok) {
              throw new Error('Network response was not ok');
          }
          return response.json();
      });
}

// Function to populate the bar chart
function populateBarChart(data) {
  // Extract data for labels and datasets
  const labels = ["January", "February", "March", "April", "May", "June"];
  const revenueData = [4215, 5312, 6251, 7841, 9821, 14984]; // Sample data, replace with fetched data

  // Prepare the data for the bar chart
  const chartData = {
      labels: labels,
      datasets: [{
          label: "Revenue",
          backgroundColor: "rgba(2,117,216,1)",
          borderColor: "rgba(2,117,216,1)",
          data: revenueData, // Use fetched revenue data here
      }],
  };

  // Populate the bar chart
  const ctx = document.getElementById('myBarChart').getContext('2d');
  const myBarChart = new Chart(ctx, {
      type: 'bar',
      data: chartData,
      options: {
          scales: {
              xAxes: [{
                  time: {
                      unit: 'month'
                  },
                  gridLines: {
                      display: false
                  },
                  ticks: {
                      maxTicksLimit: 6
                  }
              }],
              yAxes: [{
                  ticks: {
                      min: 0,
                      max: 15000,
                      maxTicksLimit: 5
                  },
                  gridLines: {
                      display: true
                  }
              }],
          },
          legend: {
              display: false
          }
      }
  });
}

// Fetch data from PHP script and populate the bar chart
fetchDataForBarChart()
  .then(data => populateBarChart(data))
  .catch(error => console.error('Error fetching data for bar chart:', error));
