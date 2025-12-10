(function (){

    obtenerTareas();
    let tareas = [];
    let filtradas = [];


    const nuevaTareaBtn = document.querySelector("#agregar-tarea");
    nuevaTareaBtn.addEventListener("click",function(){
        mostrarFormulario();
    });

    const filtros = document.querySelectorAll("#filtros input[type='radio']");

    filtros.forEach(filtro =>  {
        filtro.addEventListener("input", filtrarTareas);
    });

    function filtrarTareas(e){
        const valor = e.target.value;

        if(valor !== ""){
            filtradas = tareas.filter(tarea => tarea.estado === valor);
        } else{
            filtradas = [];
        }

        mostrarTareas();
    } 

    function totalPendientes(){
        const totalPendientes = tareas.filter(tarea => tarea.estado === "0");
        const pendientesRadio = document.querySelector("#pendientes");
        if(totalPendientes.length ===  0){
            pendientesRadio.disabled = true;
        } else {
            pendientesRadio.disabled = false;
        }
    }

    function totalCompletadas(){
        const totalCompletadas = tareas.filter(tarea => tarea.estado === "1");
        const completadasRadio = document.querySelector("#completadas");
        if(totalCompletadas.length ===  0){
            completadasRadio.disabled = true;
        }else {
            completadasRadio.disabled = false;
        }
    }

    async function obtenerTareas(){
        try{
            const id = obtenerProyecto();
            const url = `/api/tareas?url=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas = resultado.tareas;

            mostrarTareas();

            
        }catch(error){
            console.log(error);
        }
    }

    function limpiarTareas(){
        const listadoTareas = document.querySelector("#listado-tareas");
        while(listadoTareas.firstChild){
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }

    function mostrarTareas(){
        totalPendientes();
        totalCompletadas();
        limpiarTareas();

        const arrayTareas = filtradas.length ? filtradas : tareas;

        if(arrayTareas.length === 0){
            const contenedorTareas = document.querySelector("#listado-tareas")
            const textoNoTareas = document.createElement("LI");

            textoNoTareas.textContent = "No hay tareas";
            textoNoTareas.classList.add("no-tareas");

            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0: "Pendiente",
            1: "Completa"
        }

        arrayTareas.forEach(tarea => {
            const contenedorTarea = document.createElement("LI");
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add("tarea");
            
            const nombreTarea = document.createElement("P");
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function(){
                mostrarFormulario(true, {...tarea});
            }

            const opcionesDiv = document.createElement("DIV");
            opcionesDiv.classList.add("opciones");

            const btnEstadoTarea = document.createElement("BUTTON");
            btnEstadoTarea.classList.add("estado-tarea");
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`)
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function() {
                cambiarEstadoTarea({...tarea});
            }

            const btnEliminarTarea = document.createElement("BUTTON");
            btnEliminarTarea.classList.add("eliminar-tarea");
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = "Eliminar";
            btnEliminarTarea.ondblclick = function() {
                confirmarEliminarTarea({...tarea})
            }

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector("#listado-tareas");
            listadoTareas.appendChild(contenedorTarea);
            
        });

    }

    function confirmarEliminarTarea(tarea){
        Swal.fire({
            title: "¿Estás seguro que quieres eliminar esta tarea?",
            showCancelButton: true,
            confirmButtonText: "Sí",
            cancelButtonText: "No",
            }).then((result) => {
            if (result.isConfirmed) {
                eliminarTarea(tarea)
            }
        });
    }


    async function eliminarTarea(tarea){
        const {estado, id, nombre} = tarea;

        const datos = new FormData();
        datos.append("id", id);
        datos.append("nombre", nombre);
        datos.append("estado", estado);
        datos.append("proyectoId", obtenerProyecto());

        try{
            const url = "http://localhost:3000/api/tarea/eliminar"
            const respuesta = await fetch(url, {
                method: "POST",
                body: datos
            });

            const resultado = await respuesta.json();

            console.log(resultado);

            if(resultado.resultado){
                //mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector(".contenedor-nueva-tarea"));

                Swal.fire("¡Eliminado!",resultado.mensaje, "success");

                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== id);
                mostrarTareas();
            }
        }catch(error){
            console.log(error);
        }
    }

    function cambiarEstadoTarea(tarea){
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado; 
        actualizarTarea(tarea);
    }
    
    async function actualizarTarea(tarea){
        const {estado, id, nombre, proyectoId} = tarea;

        const datos = new FormData();
        datos.append("id", id);
        datos.append("nombre", nombre);
        datos.append("estado", estado);
        datos.append("proyectoId", obtenerProyecto());

        try{
            const url = "http://localhost:3000/api/tarea/actualizar"
            const respuesta = await fetch(url, {
                method: "POST",
                body: datos
            });

            const resultado = await respuesta.json();

            console.log(resultado)

            if(resultado.respuesta.tipo === "exito"){
                Swal.fire(
                    resultado.respuesta.mensaje,
                    "Tarea actualizada correctamente",
                    "success"
                );

                const modal = document.querySelector(".modal");
                if(modal){
                    modal.remove();
                } 


                tareas = tareas.map(tareaMemoria => {
                    if(tareaMemoria.id === id){
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    return tareaMemoria;
                });
                mostrarTareas();
            }


        }catch(error){
            console.log(error);
        }
    }


    function mostrarFormulario(editar = false, tarea = {}){
        console.log(editar);
        const modal = document.createElement("DIV");
        modal.classList.add("modal");
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>${editar ? "Editar tarea" : "Añade una nueva tarea"}</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input
                        type="text"
                        name="tarea"
                        placeholder="${tarea.nombre ? "Edita la tarea" : "Añade una tarea a tu proyecto actual"}"
                        id="tarea"
                        value="${tarea.nombre ? tarea.nombre : ""}"
                    />
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="${editar ? "Guardar cambios" : "Añadir tarea"}" />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;

        setTimeout(() => {
            const formulario = document.querySelector(".formulario");

            formulario.classList.add("animar");
        }, 0);

        modal.addEventListener("click", function(e){
            e.preventDefault();

            const formulario = modal.querySelector(".formulario");

            if(e.target.classList.contains("cerrar-modal")){
                formulario.classList.add("cerrar");

                setTimeout(() => {
                    modal.remove();
                }, 500);
            }

            if(e.target.classList.contains("submit-nueva-tarea")){
                const nombreTarea = document.querySelector("#tarea").value.trim();

                if(nombreTarea === ""){
                    mostrarAlerta("El nombre de la tarea es obligatorio", "error", document.querySelector(".formulario legend"));
                    return;
                }

                if(editar){
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                } else {
                    agregarTarea(nombreTarea);
                }
            }
        });


        const btnCerrarModal = document.querySelector(".cerrar-modal");

        document.querySelector(".dashboard").appendChild(modal);
    }


    function mostrarAlerta(mensaje, tipo, referencia){
        const alertaPrevia = document.querySelector(".alerta");
        if(alertaPrevia){
            alertaPrevia.remove();
        }

        const alerta = document.createElement("DIV");
        alerta.classList.add("alerta", tipo);
        alerta.textContent = mensaje;
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        setTimeout(() => {
            alerta.remove();
        }, 5000);
    }

    async function agregarTarea(tarea){
        const datos = new FormData();
        datos.append("nombre", tarea);
        datos.append("proyectoId", obtenerProyecto())    
        

        try{
            const url = "http://localhost:3000/api/tarea"
            const respuesta = await fetch(url, {
                method: "POST",
                body: datos
            });

            const resultado = await respuesta.json();

            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector(".formulario legend"));
            
            if(resultado.tipo === "exito"){
                const modal = document.querySelector(".modal");
                setTimeout(() => {
                    modal.remove();
                }, 500);

                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }

                tareas = [...tareas, tareaObj];


                mostrarTarea();
            }

        } catch(error){
            console.log(error);
        }
    }

    function obtenerProyecto(){
        const proyectoParams = new URLSearchParams(window.location.search);

        const proyecto = Object.fromEntries(proyectoParams.entries());

        console.log(proyecto.url);
        return proyecto.url;
    }

})();