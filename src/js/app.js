document.addEventListener('DOMContentLoaded', function () {
    iniciarApp();
});

function iniciarApp() {
    // IFFIE para la vista de Resultados
 
    (function () {
        const periodoInput = document.querySelector('#selectPeriodo');

        if (periodoInput) {
            cargarResultados();
        }
    })();

    // IFFIE para la vista de encuesta
  
    (function () {
        const departamentosInput = document.querySelector('#departamento');

        if (departamentosInput) {
            cargarDepartamentos();
        }
    })();

    // IFFIE para modal
 
    (function () {
        const eliminarInput = document.querySelector('#modalConfirmacion');

        if (eliminarInput) {
            mostrarModal();
        }
    })();

 
    (function () {
        const progress = document.getElementById('progress_dashboard');
        if (progress) {
            loadProgress();
        }
    })();

   
    (function () {
        const results = document.getElementById('filtro_resultados');
        if (results) {
            filterResult();
        }
    }
    )();

   
    (function () {
        const filtroEnc = document.getElementById('filtroEncuesta');
        if (filtroEnc) {
            filtroEncuestaClima();
        }
    })();

   
    (function () {
        const clave = document.getElementById('clave_depto');
        if (clave) {
            deptoClave();
        }
    }
    )();

    
    (function () {
        // Obtén el elemento select
        const selectDepto = document.getElementById('nombreDepartamento');
        
        if (selectDepto) {
            // Escucha el evento change
            selectDepto.addEventListener('change', function () {
                deptoClave(this); // Llama a la función con el select actual
            });
        }
        
    }
    )();

    (function () {
        const btnDescargar = document.getElementById('btnDescargar');
        if (btnDescargar) {
            setupDownloadButton();
        }
    })();
    
}

function setupDownloadButton() {
    const btnDescargar = document.getElementById('btnDescargar');
    btnDescargar.addEventListener('click', function () {
        const link = document.createElement('a');
        link.href = 'https://miclima.mimundodigital.mx/Archivos/Plan%20de%20c,%20ac%20y%20m%20-%20Mundo%20Imperial.xlsx'; // URL del archivo
        link.download = 'Plan de c, ac y m - Mundo Imperial.xlsx'; // Nombre con el que se descargará el archivo
        link.click(); // Simula el clic en el enlace para descargar
    });
}


function deptoClave(selectElement) {
    // Obtener el valor del atributo 'data-clave' del departamento seleccionado
    const claveDepto = selectElement.options[selectElement.selectedIndex].getAttribute('data-clave');
    
    // Actualizar el campo 'claveDepto' con la clave obtenida
    document.getElementById('claveDepto').value = claveDepto;
}



function mostrarModal() {
    const modal = document.getElementById('modalConfirmacion');
    const spanClose = modal.querySelector('.close');

    document.querySelectorAll('.table__accion--eliminar').forEach(boton => {
        boton.addEventListener('click', function (event) {
            event.preventDefault();
            modal.style.display = "block";

            const confirmar = document.getElementById('confirmarEliminar');
            confirmar.onclick = () => {
                // Aquí envías el formulario de eliminación
                this.closest('form').submit();
            };

            const cancelar = document.getElementById('cancelarEliminar');
            cancelar.onclick = () => {
                modal.style.display = "none";
            };
        });
    });

    // Event listener para cerrar el modal con el botón de cierre (X)
    if (spanClose) {
        spanClose.addEventListener('click', function () {
            modal.style.display = 'none';
        });
    }
}


async function cargarResultados() {
    const selectPeriodo = document.getElementById('selectPeriodo');
    const selectDepartamento = document.getElementById('selectDepartamento');
    const encabezadoTabla = document.getElementById('primer-encabezado');
    const propiedadId = document.getElementById('propiedadActual');

    async function actualizarResultados() {
        const periodoSeleccionado = selectPeriodo.value;
        const departamentoSeleccionado = selectDepartamento.value;
        const propiedadActual = propiedadId.value;

        if (periodoSeleccionado && departamentoSeleccionado) {
            try {
                const url = `${location.origin}/resultados/api?periodos_id=${encodeURIComponent(periodoSeleccionado)}&departamentos_id=${encodeURIComponent(departamentoSeleccionado)}&idpropiedades=${encodeURIComponent(propiedadActual)}`;
                const respuesta = await fetch(url);
                const data = await respuesta.json();

                rellenarTabla(data, departamentoSeleccionado === 'RG');
            } catch (error) {
                console.error('Error al cargar los resultados:', error);
            }
        }
    }

    function calcularPromedio(resultados) {
        let suma = 0;
        let conteo = 0;
        for (let i = 1; i <= 16; i++) {
            const valor = parseFloat(resultados['cp' + i]);
            if (!isNaN(valor)) {
                suma += valor;
                conteo++;
            }
        }
        return conteo > 0 ? (suma / conteo).toFixed(2) : 'N/A';
    }

    function determinarClasePorCalificacion(calificacion) {
        if (calificacion >= 85) {
            return 'calificacion-verde';
        } else if (calificacion >= 80) {
            return 'calificacion-amarillo';
        } else {
            return 'calificacion-rojo';
        }
    }

    function determinarClasePorDiferencia(diferencia) {
        if (diferencia === 'N/A' || diferencia === '0.00') {
            return '';
        }

        const diff = parseFloat(diferencia);
        return diff > 0 ? 'calificacion-verde' : 'calificacion-rojo';
    }

    function rellenarTabla(data, esResultadosGenerales) {
        const tbody = document.querySelector('.table__tbody');
        tbody.innerHTML = '';
        encabezadoTabla.textContent = esResultadosGenerales ? 'Departamento' : 'Pregunta';

        if (!esResultadosGenerales) {
            const preguntas = Array.from(document.querySelectorAll('#preguntas-container span')).map(span => span.dataset.pregunta);
            const resultadosFiltrados = data.resultadosFiltrados.length > 0 ? data.resultadosFiltrados[0] : null;
            const resultadoPrevio = data.resultadoPrevio || {};

            const promedioActual = resultadosFiltrados ? calcularPromedio(resultadosFiltrados) : 'N/A';
            const promedioAnterior = resultadoPrevio ? calcularPromedio(resultadoPrevio) : 'N/A';
            const diferenciaPromedio = promedioAnterior !== 'N/A' ? (parseFloat(promedioActual) - parseFloat(promedioAnterior)).toFixed(2) : 'N/A';

            const filaPromedio = document.createElement('tr');
            filaPromedio.classList.add('table__tr');
            filaPromedio.innerHTML = `
                <td class="table__td-pregunta"></td>
                <td class="table__td ${determinarClasePorCalificacion(promedioActual)}">${promedioActual !== 'N/A' ? promedioActual + '%' : 'N/A'}</td>
                <td class="table__td ${determinarClasePorDiferencia(diferenciaPromedio)}">${diferenciaPromedio !== 'N/A' ? diferenciaPromedio + '%' : 'N/A'}</td>
                <td class="table__td ${determinarClasePorCalificacion(promedioAnterior)}">${promedioAnterior !== 'N/A' ? promedioAnterior + '%' : 'N/A'}</td>
            `;
            tbody.appendChild(filaPromedio);

            preguntas.forEach((pregunta, index) => {
                const valorActual = resultadosFiltrados ? parseFloat(resultadosFiltrados['cp' + (index + 1)]) : 'N/A';
                const valorPrevio = resultadoPrevio['cp' + (index + 1)] ? parseFloat(resultadoPrevio['cp' + (index + 1)]) : 'N/A';
                const diferencia = valorActual !== 'N/A' && valorPrevio !== 'N/A' ? (valorActual - valorPrevio).toFixed(2) : 'N/A';

                const fila = document.createElement('tr');
                fila.classList.add('table__tr');
                fila.innerHTML = `
                    <td class="table__td-pregunta">${pregunta}</td>
                    <td class="table__td ${determinarClasePorCalificacion(valorActual)}">${valorActual !== 'N/A' ? valorActual + '%' : 'N/A'}</td>
                    <td class="table__td ${determinarClasePorDiferencia(diferencia)}">${diferencia !== 'N/A' ? diferencia + '%' : 'N/A'}</td>
                    <td class="table__td ${determinarClasePorCalificacion(valorPrevio)}">${valorPrevio !== 'N/A' ? valorPrevio + '%' : 'N/A'}</td>
                `;
                tbody.appendChild(fila);
            });
        } else {
            // Mapear los departamentos del div oculto
            const departamentos = Array.from(document.querySelectorAll('#departamentos-container span')).map(span => {
                console.log(span.dataset)
                return {
                    claveDepto: span.dataset.clavedepto,
                    periodos_id: span.dataset.periodos_id,
                    nombreDepartamento: span.dataset.nombredepartamento
                };
            });

            // Iterar sobre cada departamento para crear las filas de la tabla
            departamentos.forEach(departamento => {
                // Buscar los resultados correspondientes al departamento actual
                const resultadoActual = data.resultadosFiltrados.find(r => r.departamentos_id.toString() === departamento.claveDepto) || {};
                const resultadoPrevio = data.resultadoPrevio.find(r => r.departamentos_id.toString() === departamento.claveDepto) || {};

                // Calcular promedios y diferencia
                const promedioActual = Object.keys(resultadoActual).length ? calcularPromedio(resultadoActual) : 'N/A';
                const promedioAnterior = (resultadoPrevio.departamentos_id === 'N/A') ? 'N/A' : (Object.keys(resultadoPrevio).length ? calcularPromedio(resultadoPrevio) : 'N/A');
                const diferencia = (promedioAnterior !== 'N/A' && promedioActual !== 'N/A') ? (parseFloat(promedioActual) - parseFloat(promedioAnterior)).toFixed(2) : 'N/A';

                // Crear y añadir la fila a la tabla
                const fila = document.createElement('tr');
                fila.classList.add('table__tr');
                fila.innerHTML = `
                    <td class="table__td-pregunta">${departamento.nombreDepartamento}</td>
                    <td class="table__td ${determinarClasePorCalificacion(promedioActual)}">${promedioActual}%</td>
                    <td class="table__td ${determinarClasePorDiferencia(diferencia)}">${diferencia}%</td>
                    <td class="table__td ${determinarClasePorCalificacion(promedioAnterior)}">${promedioAnterior}%</td>
                `;
                tbody.appendChild(fila);
            });
        }
    }


    selectPeriodo.addEventListener('change', actualizarResultados);
    selectDepartamento.addEventListener('change', actualizarResultados);
}


async function cargarDepartamentos() {
    try {
        const urlDepartamentos = `${location.origin}/encuesta/api`;
        const urlPeriodos = `${location.origin}/encuesta/periodos`;

        const responseDepartamentos = await fetch(urlDepartamentos);
        const responsePeriodos = await fetch(urlPeriodos);

        const dataDepartamentos = await responseDepartamentos.json();
        const dataPeriodos = await responsePeriodos.json();

        const departamentos = dataDepartamentos.departamentos; // Lista de departamentos
        const periodos = dataPeriodos.periodos; // Lista de periodos

        const propiedadSelect = document.getElementById('propiedad');
        const departamentoSelect = document.getElementById('departamento');
        const periodoInput = document.getElementById('periodo'); // Input oculto para el periodo

        // Función para llenar el select de departamentos
        function llenarDepartamentos() {
            const propiedadSeleccionada = propiedadSelect.value;
            const periodoSeleccionado = periodoInput.value;

            // Limpiar el select de departamentos
            departamentoSelect.innerHTML = '<option value="">-- Seleccione --</option>';

            if (!propiedadSeleccionada || !periodoSeleccionado) {
                return; // Salir si no hay propiedad o periodo seleccionado
            }

            const departamentosFiltrados = departamentos.filter(departamento =>
                departamento.propiedades_id == propiedadSeleccionada &&
                departamento.periodos_id == periodoSeleccionado
            );

            departamentosFiltrados.forEach(departamento => {
                const option = document.createElement('option');
                option.value = departamento.claveDepto;
                option.textContent = departamento.nombreDepartamento;
                departamentoSelect.appendChild(option);
            });
        }

        // Evento para actualizar departamentos cuando cambia la propiedad
        propiedadSelect.addEventListener('change', llenarDepartamentos);

        // Inicializar con los valores por defecto
        llenarDepartamentos();

    } catch (error) {
        console.error('Error al cargar datos:', error);
    }
}



function loadProgress() {
    document.querySelectorAll('.table__progreso').forEach(function (element) {
        const porcentaje = element.getAttribute('data-porcentaje');
        element.style.width = porcentaje + '%';
    })

}

const filterResult = async() => {
    const selectPeriodo = document.getElementById('selectPeriodo');
    const selectDepartamento = document.getElementById('selectDepartamento');
    const inputPlanAC = document.getElementById('planAC');
    const departamentoIdInput = document.getElementById('dep');
    const periodoIdInput = document.getElementById('per');
    const inputDepartamento = document.getElementById('depar');
    const inputPeriodo = document.getElementById('perio');
    const inputDepartamento1 = document.getElementById('dep1');
    const inputPeriodo1 = document.getElementById('per1');
    const periodoPropiedadInput = document.getElementById('perpro');
    const periodoPropiedad2Input = document.getElementById('perpro2');
    const dashboardContenedor = document.getElementById('dashboardContenedor');
    const dashboardContenedor1 = document.getElementById('dashboardContenedor1');

    const cuestionarioDiv = document.getElementById('preguntas-container');
    const departamentosDiv = document.getElementById('departamentos-container');
    let preguntas;
    let departamentos;
    dashboardContenedor.style.display = 'none';
        dashboardContenedor1.style.display = 'none';
    function actualizarContenido() {
        const periodoSeleccionado = selectPeriodo.value;
        const departamentoSeleccionado = selectDepartamento.value;

        if (!periodoSeleccionado) {
            cuestionarioDiv.style.display = "none";
            departamentosDiv.style.display = "none";
            return;
        }

        if (departamentoSeleccionado === 'RG') {
            desplegarDepartamentos();
            cuestionarioDiv.style.display = "none";
        } else if (departamentoSeleccionado !== '') {
            desplegarPreguntas();
            departamentosDiv.style.display = "none";
        } else {
            cuestionarioDiv.style.display = "none";
            departamentosDiv.style.display = "none";
        }
    }

    function desplegarPreguntas() {
        const periodoSeleccionado = selectPeriodo.value;
        if (!periodoSeleccionado) return;

        cuestionarioDiv.innerHTML = '';
        const preguntasFiltradas = preguntas.filter(pregunta => pregunta.idperiodo == periodoSeleccionado);
        preguntasFiltradas.forEach(pregunta => {
            const span = document.createElement('span');
            span.dataset.id = pregunta.id;
            span.dataset.idpropiedades = pregunta.idpropiedades;
            span.dataset.idperiodo = pregunta.idperiodo;
            span.dataset.pregunta = pregunta.pregunta;
            cuestionarioDiv.appendChild(span);
        });
        cuestionarioDiv.style.display = preguntasFiltradas.length > 0 ? "block" : "none";
    }

    function desplegarDepartamentos() {
        const periodoSeleccionado = selectPeriodo.value;
        if (!periodoSeleccionado) return;
    
        departamentosDiv.innerHTML = '';
        
        // Filtramos los departamentos por periodo
        const departamentosFiltrados = departamentos.filter(departamento => departamento.periodos_id == periodoSeleccionado);
    
        // Ordenamos los departamentos por claveDepto
        departamentosFiltrados.sort((a, b) => {
            if (a.claveDepto < b.claveDepto) {
                return -1;
            }
            if (a.claveDepto > b.claveDepto) {
                return 1;
            }
            return 0;
        });
    
        // Creamos los elementos DOM para los departamentos
        departamentosFiltrados.forEach(departamento => {
            const span = document.createElement('span');
            span.setAttribute('data-claveDepto', departamento.claveDepto);
            span.setAttribute('data-periodos_id', departamento.periodos_id);
            span.setAttribute('data-nombreDepartamento', departamento.nombreDepartamento);
            departamentosDiv.appendChild(span);
        });
    
        // Mostramos u ocultamos el contenedor dependiendo de si hay departamentos
        departamentosDiv.style.display = departamentosFiltrados.length > 0 ? "block" : "none";
    }

    function fetchPlanAC() {
        const periodoId = selectPeriodo.value;
        const departamentoId = selectDepartamento.value;

        if (periodoId && departamentoId) {
            // Realiza la solicitud AJAX para obtener el PlanAC
            $.ajax({
                url: '/verificar-plan',
                method: 'POST',
                data: {
                    periodo: periodoId,
                    departamento: departamentoId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.planAC === null || response.planAC === '' || response.planAC === 'NULL') {
                        inputPlanAC.value = 'NULL';
                    } else {
                        inputPlanAC.value = response.planAC;
                    }
                },
                error: function() {
                    inputPlanAC.value = 'Error al obtener el PlanAC';
                }
            });
        }
    }

    function actualizarInputs() {
        const resultadosContainer = document.getElementById('tabla_resultados').getElementsByTagName('tbody')[0];
        const selectedDepartamentoId = selectDepartamento.value;
        const selectedPeriodoId = selectPeriodo.value;
        departamentoIdInput.value = selectedDepartamentoId;
        inputDepartamento.value = selectedDepartamentoId;
        inputDepartamento1.value = selectedDepartamentoId;

        periodoIdInput.value = selectedPeriodoId;
        inputPeriodo.value = selectedPeriodoId;
        inputPeriodo1.value = selectedPeriodoId;

        const selectedOption = selectPeriodo.options[selectPeriodo.selectedIndex];
        const propiedadId = selectedOption.getAttribute('data-propiedad');
        periodoPropiedadInput.value = propiedadId;
        // periodoPropiedad2Input.value = propiedadId;

        resultadosContainer.innerHTML = ''; // Limpiar resultados
    }

    function esUltimoPeriodo() {
        const selectedPeriodo = selectPeriodo.options[selectPeriodo.selectedIndex];
        const selectedPeriodoId = selectedPeriodo.value;
        const selectedPropiedadId = selectedPeriodo.dataset.propiedad;
        const periodosMismaPropiedad = Array.from(selectPeriodo.options).filter(option => option.dataset.propiedad === selectedPropiedadId);
        const ultimoPeriodoMismaPropiedad = periodosMismaPropiedad[periodosMismaPropiedad.length - 1];

        return ultimoPeriodoMismaPropiedad && ultimoPeriodoMismaPropiedad.value === selectedPeriodoId;
    }

    function limpiarContenedores() {
        dashboardContenedor.style.display = 'none';
        dashboardContenedor1.style.display = 'none';
    }

    function actualizarVisibilidadContenedor() {
        const periodoId = selectPeriodo.value;
        const departamentoId = selectDepartamento.value;

        if (departamentoId === 'RG') {
            dashboardContenedor.style.display = 'none';
            dashboardContenedor1.style.display = 'none';
            return;
        }

        if (periodoId && departamentoId) {
            if (esUltimoPeriodo()) {
                $.ajax({
                    url: '/verificar-plan', // Ajusta esta URL a la de tu endpoint
                    method: 'POST',
                    data: {
                        periodo: periodoId,
                        departamento: departamentoId
                    },
                    success: function(response) {
                        const data = typeof response === 'string' ? JSON.parse(response) : response;

                            if (data.planAC === 'NULL' || data.planAC === null || data.planAC === '') {
                                dashboardContenedor.style.display = 'block';
                                dashboardContenedor1.style.display = 'none';
                            } else {
                                dashboardContenedor.style.display = 'none';
                                dashboardContenedor1.style.display = 'block';
                            }     
                        
                    },
                    error: function() {
                        console.error('Error al verificar PlanAC en la base de datos.');
                    }
                });
            } else {
                dashboardContenedor.style.display = 'none';
                dashboardContenedor1.style.display = 'block';
            }
        } 
    }
 
    function actualizarDepartamentos() {
        const periodoId = selectPeriodo.value;
        selectDepartamento.innerHTML = '<option selected value="">-- Departamento --</option>';
    
        if (periodoId) {
            fetch(`/obtenerdepto?periodos_id=${periodoId}`)
                .then(response => response.json())
                .then(data => {
                    // Ordenar los departamentos por claveDepto
                    const departamentosOrdenados = data.departamentos.sort((a, b) => {
                        if (a.claveDepto < b.claveDepto) return -1;
                        if (a.claveDepto > b.claveDepto) return 1;
                        return 0;
                    });
    
                    // Agregar los departamentos al select
                    departamentosOrdenados.forEach(departamento => {
                        const option = document.createElement('option');
                        option.value = departamento.claveDepto;
                        option.textContent = departamento.nombreDepartamento;
                        selectDepartamento.appendChild(option);
                    });
    
                    // Agregar opción "Resultados Generales" al final
                    const optionRG = document.createElement('option');
                    optionRG.value = 'RG';
                    optionRG.textContent = 'Resultados Generales';
                    selectDepartamento.appendChild(optionRG);
                })
                .catch(error => {
                    console.error('Error al obtener los departamentos:', error);
                });
        }
    }
    
    async function cargarDatos() {
        try {
            const preguntasResponse = await fetch(`${location.origin}/resultados/index`);
            const preguntasData = await preguntasResponse.json();
            const departamentosResponse = await fetch(`${location.origin}/resultados/departamentos`);
            const departamentosData = await departamentosResponse.json();

            preguntas = preguntasData.preguntas;
            departamentos = departamentosData.departamentos;
        } catch (error) {
            console.error('Error al cargar datos');
        }
    }
    
    // Inicializa los datos
       function manejarCambios() {
        fetchPlanAC();
        actualizarVisibilidadContenedor();
        actualizarInputs();
        actualizarContenido();
    }
    async function managePeriodo(){
        await cargarDatos();
        actualizarDepartamentos();
        actualizarInputs();
        limpiarContenedores();
    }
    selectPeriodo.addEventListener('change', managePeriodo);
    selectDepartamento.addEventListener('change', manejarCambios);

    const $btnExportar = document.querySelector("#btnExportar"),
    $tabla = document.querySelector("#tabla_resultados");

$btnExportar.addEventListener("click", function() {
    let tableExport = new TableExport($tabla, {
        exportButtons: false, // No queremos botones
        filename: "Reporte MiClima", //Nombre del archivo de Excel
        sheetname: "Reporte MiClima", //Título de la hoja
    });
    let datos = tableExport.getExportData();
    let preferenciasDocumento = datos.tabla_resultados.xlsx;
    tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
});


}


function filtroEncuestaClima() {
    cargarDatos();

    async function cargarDatos() {
        try {
            const preguntasUrl = `${location.origin}/encuesta/preguntas`;
            const periodosUrl = `${location.origin}/encuesta/periodos`;

            const [preguntasResponse, periodosResponse] = await Promise.all([
                fetch(preguntasUrl),
                fetch(periodosUrl)
            ]);

            if (!preguntasResponse.ok || !periodosResponse.ok) {
                throw new Error('Network response was not ok');
            }

            const preguntasData = await preguntasResponse.json();
            const periodosData = await periodosResponse.json();

            const preguntas = preguntasData.preguntas;
            const periodos = periodosData.periodos;

            const propiedadSelect = document.getElementById('propiedad');
            const periodoSelect = document.getElementById('periodo');
            const propiedadesInput = document.getElementById('propiedades');
            const cuestionarioDiv = document.getElementById('cuestionario');

            propiedadSelect.addEventListener('change', function () {
                actualizarPeriodos(periodos, propiedadSelect.value);
                periodoSelect.dispatchEvent(new Event('change'));
            });

            periodoSelect.addEventListener('change', function () {
                if (periodoSelect.value) {
                    desplegarPreguntas(preguntas, propiedadSelect.value, periodoSelect.value);
                } else {
                    cuestionarioDiv.style.display = "none";
                }
            });

            propiedadSelect.addEventListener('change', function () {
                actualizarPropiedad(propiedadSelect.value);
            });

            function actualizarPeriodos(periodos, propiedadId) {
                const periodosFiltrados = periodos.filter(periodo => periodo.propiedades_id == propiedadId);
                const ultimoPeriodo = periodosFiltrados.reduce((max, periodo) =>
                    new Date(periodo.periodo) > new Date(max.periodo) ? periodo : max, periodosFiltrados[0]);

                periodoSelect.innerHTML = periodosFiltrados.map(periodo =>
                    `<option value="${periodo.id}">${periodo.id}</option>`).join('');

                if (ultimoPeriodo) {
                    periodoSelect.value = ultimoPeriodo.id;
                } else {
                    periodoSelect.innerHTML = '<option value="" disabled selected>No hay periodos disponibles</option>';
                }

            }

            function actualizarPropiedad(propiedadId) {
                propiedadesInput.value = propiedadId;
            }

            function desplegarPreguntas(preguntas, propiedadId, periodoId) {
                // Limpia el contenido existente del div de cuestionario
                cuestionarioDiv.innerHTML = '';

                // Crea un elemento de encabezado con el año actual
                const header = document.createElement('h3');
                header.textContent = `Evaluación MI Clima Laboral ${new Date().getFullYear()}`;
                cuestionarioDiv.appendChild(header);

                // Filtra las preguntas según la propiedad y el periodo seleccionados
                const preguntasFiltradas = preguntas.filter(pregunta => pregunta.idpropiedades === propiedadId && pregunta.idperiodo === periodoId);

                // Itera sobre las preguntas filtradas para crear los elementos HTML
                preguntasFiltradas.forEach((pregunta, index) => {
                    const div = document.createElement('div');
                    div.classList.add('col');

                    const p = document.createElement('p');
                    p.classList.add('pregunta');
                    p.innerHTML = `<span>${pregunta.idResultado}. </span>${pregunta.pregunta}`;
                    div.appendChild(p);

                    // Agrega las opciones de respuesta
                    const opcionesHTML = `
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="p${pregunta.idResultado}" id="p${pregunta.idResultado}-positivo" value="Positivo" required>
                        <label class="form-check-label" for="p${pregunta.idResultado}-positivo">Totalmente de acuerdo</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="p${pregunta.idResultado}" id="p${pregunta.idResultado}-positivo2" value="Positivo">
                        <label class="form-check-label" for="p${pregunta.idResultado}-positivo2">De acuerdo</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="p${pregunta.idResultado}" id="p${pregunta.idResultado}-neutro" value="Neutro">
                        <label class="form-check-label" for="p${pregunta.idResultado}-neutro">Ni en acuerdo, ni en desacuerdo</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="p${pregunta.idResultado}" id="p${pregunta.idResultado}-negativo" value="Negativo">
                        <label class="form-check-label" for="p${pregunta.idResultado}-negativo">En desacuerdo</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="p${pregunta.idResultado}" id="p${pregunta.idResultado}-negativo2" value="Negativo">
                        <label class="form-check-label" for="p${pregunta.idResultado}-negativo2">Totalmente en desacuerdo</label>
                    </div>
                `;
                    div.innerHTML += opcionesHTML;

                    // Agrega el div de la pregunta al div del cuestionario
                    cuestionarioDiv.appendChild(div);

                    // Agrega el event listener a las opciones de respuesta
                    const opciones = div.querySelectorAll('input[type="radio"]');
                    opciones.forEach(opcion => {
                        opcion.addEventListener('change', function () {
                            document.getElementById(`p${index + 1}`).value = this.value;
                        });
                    });
                });

                // Establece la visualización del cuestionario dependiendo de si hay preguntas filtradas o no
                cuestionarioDiv.style.display = preguntasFiltradas.length > 0 ? "block" : "none";
            }

        } catch (error) {
            console.error('Error al cargar datos:', error);
        }
    }
    document.getElementById('formulario-encuesta').addEventListener('submit', function (event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma predeterminada

        // Verifica si todas las preguntas han sido respondidas
        const preguntas = document.querySelectorAll('#cuestionario input[type="radio"]');

        // Comprobar si hay alguna pregunta sin respuesta
        const todasRespondidas = Array.from(preguntas).every(radio => {
            const nombrePregunta = radio.name; // Obtener el nombre de la pregunta
            return document.querySelector(`input[name="${nombrePregunta}"]:checked`) !== null; // Verificar si está seleccionada
        });

        if (!todasRespondidas) {
            alert('Por favor, responda todas las preguntas.'); // Alerta si falta alguna respuesta
            return; // Evita el envío del formulario
        }

        // Recopila los datos del formulario
        const formData = new FormData(this);

        // Envía los datos al servidor utilizando AJAX
        fetch('/encuesta', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (response.ok) {
                    console.log('Datos enviados correctamente');
                    window.location.href = '/respuestas-enviadas'; // Redirige a una página de éxito
                } else {
                    console.error('Error al enviar datos');
                }
            })
            .catch(error => {
                console.error('Error de red al enviar datos:', error);
            });
    });

}






