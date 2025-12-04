(function (){

    obtenerTareas();


    const nuevaTareaBtn = document.querySelector("#agregar-tarea");
    nuevaTareaBtn.addEventListener("click", mostrarFormulario);

    async function obtenerTareas(){
        try{
            const id = obtenerProyecto();
            const url = `/api/tareas?url=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            const {tareas} = resultado;

            mostrarTareas(tareas);

            
        }catch(error){
            console.log(error);
        }
    }

    function mostrarTareas(tareas){
        
    }

    function mostrarFormulario(){
        const modal = document.createElement("DIV");
        modal.classList.add("modal");
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>Añade una nueva tarea</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input
                        type="text"
                        name="tarea"
                        placeholder="Añade una tarea a tu proyecto actual"
                        id="tarea"
                    />
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="Añadir tarea" />
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
                submitFormularioNuevaTarea();
            }
        });


        const btnCerrarModal = document.querySelector(".cerrar-modal");

        document.querySelector(".dashboard").appendChild(modal);
    }

    function submitFormularioNuevaTarea(){
        const tarea = document.querySelector("#tarea").value.trim();

        if(tarea === ""){
            mostrarAlerta("El nombre de la tarea es obligatorio", "error", document.querySelector(".formulario legend"));
            return;
        }

        agregarTarea(tarea);
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
                }, 1500);
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