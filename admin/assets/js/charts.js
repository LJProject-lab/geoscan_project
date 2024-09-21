// Fetch the data from the embedded script tags
const internData = JSON.parse(document.getElementById('internData').textContent);
const coordinatorData = JSON.parse(document.getElementById('coordinatorData').textContent);
const timeLogData = JSON.parse(document.getElementById('timeLogData').textContent);

// Interns per Month Chart
const internMonths = internData.map(item => item.month);
const internCounts = internData.map(item => item.intern_count);

const ctxIntern = document.getElementById('internChart').getContext('2d');
const internChart = new Chart(ctxIntern, {
    type: 'bar',
    data: {
        labels: internMonths,
        datasets: [{
            label: 'Interns',
            data: internCounts,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});

// Coordinators per Month Chart
const coordinatorMonths = coordinatorData.map(item => item.month);
const coordinatorCounts = coordinatorData.map(item => item.coordinator_count);

const ctxCoordinator = document.getElementById('coordinatorChart').getContext('2d');
const coordinatorChart = new Chart(ctxCoordinator, {
    type: 'bar',
    data: {
        labels: coordinatorMonths,
        datasets: [{
            label: 'Coordinators',
            data: coordinatorCounts,
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});

// Time Logs per Day Chart
const timeLogDates = timeLogData.map(item => item.date);
const logCounts = timeLogData.map(item => item.log_count);

const ctxTimeLogs = document.getElementById('timeLogChart').getContext('2d');
const timeLogChart = new Chart(ctxTimeLogs, {
    type: 'line',
    data: {
        labels: timeLogDates,
        datasets: [{
            label: 'Time Logs',
            data: logCounts,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});
