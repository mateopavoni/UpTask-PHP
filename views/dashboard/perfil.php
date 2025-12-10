<?php include_once __DIR__ . "/header-dashboard.php"; ?>
    <div class="contenedor-sm">
        <?php  include_once __DIR__ . "/../templates/alertas.php" ?>

        <a href="/cambiar-password" class="enlace">Cambiar Password</a>

        <form class="formulario" method="POST" action="/perfil">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    placeholder="Tu nombre"
                    value="<?php echo s($usuario->nombre); ?>"
                />
            </div>

            <div class="campo">
                <label for="apellido">Apellido</label>
                <input
                    type="text"
                    id="apellido"
                    name="apellido"
                    placeholder="Tu apellido"
                    value="<?php echo s($usuario->apellido); ?>"
                />
            </div>

            <div class="campo">
                <label for="email">email</label>
                <input
                    type="text"
                    id="email"
                    name="email"
                    placeholder="Tu email"
                    value="<?php echo s($usuario->email); ?>"
                />
            </div>

            <input type="submit" value="Guardar Cambios">
        </form>
    </div>
<?php include_once __DIR__ . "/footer-dashboard.php"; ?>