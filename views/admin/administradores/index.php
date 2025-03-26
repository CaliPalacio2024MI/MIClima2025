
<h1>Administradores de Talento Humano</h1>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/administradores-crear">
        <i class="fa-solid fa-circle-plus"></i>
        Agregar Administrador de TH
    </a>
</div>

<div class="dashboard__contenedor">
<?php if(!empty($administradores)) { ?>
    <table class="table">
        <thead class="table__thead">
            <tr>
                <th scope="col" class="table__th">RFC</th>
                <th scope="col" class="table__th">Nombre</th>
                <th scope="col" class="table__th">Propiedad</th>
                <th scope="col" class="table__th"></th>
            </tr>
        </thead>

        <tbody class="table__tbody">
            <?php foreach($administradores as $administrador) { ?>
                <tr class="table__tr">
                    <td class="table__td">
                        <?php echo $administrador->id; ?>
                    </td>
                    <td class="table__td">
                        <?php echo $administrador->nombreAnfitrion; ?>
                    </td>
                    <td class="table__td">
                        <?php echo $administrador->propiedad->nombrePropiedad; ?>
                    </td>

                    <td class="table__td--acciones">
                        <div class="table__td--acciones-th-div">
                            <a class="table__accion table__accion--editar" href="/admin/administradores-actualizar?id=<?php echo $administrador->id; ?>">
                                <!-- <i class="fa-solid fa-user-pen"></i> -->
                                <i class="fa-solid fa-user-pen"></i>
                                Editar
                            </a>

                            <form method="POST" action="/admin/administradores-eliminar" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $administrador->id; ?>">
                                <button class="table__accion table__accion--eliminar" type="submit">
                                    <i class="fa-solid fa-user-slash"></i>
                                    <!-- <i class="fa-solid fa-circle-xmark"></i> -->
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <p class="text-center">Aún No Hay Administradores de Talento Humano</p>
<?php } ?>
</div>



