var maxSize = [];
var HWM = [];
const availableSize = [];

(async () => {
try{
  const responseRaw = await fetch("./tablespacesMonitorRequests.php");
  const response = await responseRaw.json();
  
  const optionsDoughnut = {
    type: 'doughnut',    
    data: {
      labels: response.names,
      datasets: [{
        data: response.maxSize,
        backgroundColor: ['#141868', '#581845', '#900C3F', '#C70039', '#FF5733', '#FFC300', '#259710', '#DAF7A6']
      }]
    },
    options: {
      plugins: {
        legend: {
          display: false,
        },
        labels: [{
          fontColor: "white",
          render: 'percentage',
          position: 'inside',
          render: function(args) {
            return `${args.label}`
          },
        }]
      }
    }
  }

const ctxD = document.getElementById('myChart1').getContext('2d');
new Chart(ctxD, optionsDoughnut);

} catch(e){
  console.log(e);
}
})();


(async () => {
try{
  const responseRaw = await fetch("./tablespacesMonitorRequests.php");
  const response = await responseRaw.json();

  maxSize = response.maxSize;
  HWM = response.hwm;

  for (let i = 0; i < maxSize.length; i++) { 
    availableSize.push(maxSize[i] - HWM[i]);
  }

  const options = {
    type: 'bar',
    data: {
      labels: response.names,
      datasets: [{
          label: 'Used (MB)',
          data: response.usedSize,
          backgroundColor: 'rgb(88, 24, 69)',
        },
        {
          label: 'Free (MB)',
          data: response.freeSize,
          backgroundColor: 'rgb(255, 87, 51)',
          borderColor: 'rgb(255, 255, 255, 1)',
          borderWidth: {
            top: 0,
            right: 5,
            bottom: 0,
            left: 0
         }
        },
        {
          label: 'Available (MB)',
          data: availableSize,
          backgroundColor: 'rgb(255, 195, 0)',
          borderColor: 'rgb(255, 0, 0, 1)',
          borderWidth: {
            top: 0,
            right: 5,
            bottom: 0,
            left: 0
         }
        }
      ]
    },
    options: {
      indexAxis: 'y',
      plugins: {
        legend: {
          labels: {
            color: 'white'
          }
        }
      },
      scales: {
        y: {
          stacked: true,
          ticks: { 
            color: 'white', 
            beginAtZero: true 
          }
        },
        x: {
          stacked: true,
          ticks: { 
            color: 'white', 
            beginAtZero: true 
          }
        }
      }
    }
  }
  
const ctx = document.getElementById('myChart2').getContext('2d');
new Chart(ctx, options);

} catch(e){
  console.log(e);
}
})();