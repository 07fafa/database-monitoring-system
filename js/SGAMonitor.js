const labels = [];
const usedMemoryData = [];
const HWMData = [];
var num = 10;

setInterval(function() {
    (async () => {
        // Llamar a nuestra API. Puedes usar cualquier librería para la llamada, yo uso fetch, que viene nativamente en JS
        const responseRaw = await fetch("./SGAMonitorRequests.php");
        // Decodificar como JSON
        const response = await responseRaw.json();
        // Ahora ya tenemos las etiquetas y datos dentro de "respuesta"
        // Obtener una referencia al elemento canvas del DOM
        const $chart = document.querySelector("#myChart");

        //Almacenar los datos dentro de los arrays
        labels.push(response.hours); // <- Aquí estamos pasando el valor traído usando AJAX
        if(labels.length > num){
            labels.splice(0, 1);
        }

        usedMemoryData.push(response.random); // <- Aquí estamos pasando el valor traído usando AJAX
        if(usedMemoryData.length > num){
            usedMemoryData.splice(0, 1);
        }

        HWMData.push(response.hwm); // <- Aquí estamos pasando el valor traído usando AJAX
        if(HWMData.length > num){
            HWMData.splice(0, 1);
        }

        // Conjuntos de datos
        const usedMemory = {
            label: "Memory Used (MB)",
            data: usedMemoryData, // <- Aquí estamos pasando los valores del array
            backgroundColor: 'rgba(0, 183, 255, 0)', // Color de fondo
            borderColor: 'rgb(0, 183, 255, 1)', // Color del borde
            borderWidth: 2, // Ancho del borde
        };

        const hwm = {
            label: "HWM (MB)",
            data: HWMData,
            backgroundColor: 'rgba(251, 255, 0, 0)',
            borderColor: 'rgb(251, 255, 0, 1)',
            borderWidth: 2,
        };

        new Chart($chart, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    usedMemory,
                    hwm
                ]
            },
            options: {
                showLines: true,
                scales: {
                    yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true,
                            fontColor: 'white'
                        }
                    }],
                    xAxes: [{
                        display: true,
                        ticks: {
                            fontColor: 'white'
                        }
                    }]
                },
                legend: {
                    labels: {
                        fontColor: 'white',
                        fontSize: 16
                    }
                }
            }
        });

        function isAlert() {
            $dateTime = response.date + ' ' + response.hours;
            if (response.random >= response.hwm) {
                var $form = $('<form action="SGAMonitorReport.php" method="post" target="_blank"></form>');
                var $span = $('<span class="input-group-text alert-margin-top"></span>');
                var $img = $('<img src="../DBA_Project/img/Alert.png" width="15%">');
                var $input = $('<input type="text" class="form-control" value="' + $dateTime + '" name="alert">');
                var $inputButton = $('<input type="submit" value="Open" class="btn btn-danger">');
                $span.append($img, $input, $inputButton);
                $form.append($span);
                $("#formAlert").append($form);
            }
        }

        isAlert();
    })();
}, 1000);