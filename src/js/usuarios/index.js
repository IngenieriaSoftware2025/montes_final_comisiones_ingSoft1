import { Dropdown } from "bootstrap"; //si utilizo dropdown en mi layaut  (Dropdown es un funcion interna del MVC)
import Swal from "sweetalert2"; //para utilizar las alertas
import DataTable from "datatables.net-bs5";
import { validarFormulario, Toast } from "../funciones"; //(validarFormulario y Toast son funciones internas del MVC)
import { lenguaje } from "../lenguaje"; //(lenguaje es un funcion interna del MVC)
import { error } from "jquery";

const FormUsuarios = document.getElementById('FormUsuarios');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const validarTelefono = document.getElementById('usuario_tel');
const validarDpi = document.getElementById('usuario_dpi');

const validacionTelefono = () => {

    const cantidadDigitos = validarTelefono.value

    if (cantidadDigitos.length < 1) {

        validarTelefono.classList.remove('is-valid', 'is-invalid');

    } else {
        if (cantidadDigitos.length < 8 || cantidadDigitos.length > 9) {
            Swal.fire({
                position: "center",
                icon: "warning",
                title: "Revise el número de teléfono",
                text: "La cantidad de dígitos debe ser entre 8 y 9 dígitos",
                showConfirmButton: false,
                timer: 3000
            })

            validarTelefono.classList.remove('is-valid');
            validarTelefono.classList.add('is-invalid');

        } else {
            validarTelefono.classList.remove('is-invalid');
            validarTelefono.classList.add('is-valid');
        }
    }

}

const validacionDpi = () => {

    const cantidadDigitos = validarDpi.value

    if (cantidadDigitos.length < 1) {

        validarDpi.classList.remove('is-valid', 'is-invalid');

    } else {
        if (cantidadDigitos.length < 13 || cantidadDigitos.length > 13) {
            Swal.fire({
                position: "center",
                icon: "warning",
                title: "Revise el número de DPI",
                text: "La cantidad de dígitos debe ser igual a 13 dígitos",
                showConfirmButton: false,
                timer: 3000
            })

            validarDpi.classList.remove('is-valid');
            validarDpi.classList.add('is-invalid');

        } else {
            validarDpi.classList.remove('is-invalid');
            validarDpi.classList.add('is-valid');
        }
    }

}

const guardarUsuario = async e => {
  e.preventDefault();
  
  try {

    const body = new FormData(FormUsuarios)
    const url = "/montes_final_comisiones_ingSoft1/usuarios"
    const config = {
      method: 'POST',
      body
    }

    const respuesta = await fetch(url, config);
    const data = await respuesta.json();
    const { codigo, mensaje, detalle } = data;
        console.log(data)
    let icon = 'info'
    if (codigo == 1) {
      icon = 'success'
      FormUsuarios.reset()
      BuscarUsuarios();

    } else if (codigo == 2) {
      icon = 'warning'

      console.log(detalle);
    } else if (codigo == 0) {
      icon = 'error'
      console.log(detalle);

    }

    Toast.fire({
      icon,
      title: mensaje
    })

  } catch (error) {
    console.log(error);
  }

}


const datatable = new DataTable('#TableUsuarios', {
    dom: `
        <"row mt-3 justify-content-between"
            <"col" l>
            <"col" B>
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between"
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    columns: [
        {
            title: 'No.',
            data: 'usuario_id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        {
            title: 'Foto',
            data: 'usuario_fotografia',
            width: '10%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                if (data && data !== null && data !== '') {
                    // Cambiar la ruta para que apunte correctamente
                    return `<img src="${data}" alt="Foto usuario" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjUwIiBoZWlnaHQ9IjUwIiBmaWxsPSIjNjY2NjY2Ii8+CjxwYXRoIGQ9Ik0yNSAyM0MyNy43NjE0IDIzIDMwIDIwLjc2MTQgMzAgMThDMzAgMTUuMjM4NiAyNy43NjE0IDEzIDI1IDEzQzIyLjIzODYgMTMgMjAgMTUuMjM4NiAyMCAxOEMyMCAyMC43NjE0IDIyLjIzODYgMjMgMjUgMjNaIiBmaWxsPSJ3aGl0ZSIvPgo8cGF0aCBkPSJNMjUgMjVDMjAuMDI5NCAyNSAxNiAyOS4wMjk0IDE2IDM0VjM3SDM0VjM0QzM0IDI5LjAyOTQgMjkuOTcwNiAyNSAyNSAyNVoiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=';">`;
                } else {
                    return `<div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-person text-white"></i>
                            </div>`;
                }
            }
        },
        { title: 'Primer Nombre', data: 'usuario_nom1' },
        { 
            title: 'Segundo Nombre', 
            data: 'usuario_nom2',
            render: (data, type, row, meta) => {
                return data && data !== null ? data : '-';
            }
        },
        { title: 'Primer Apellido', data: 'usuario_ape1' },
        { 
            title: 'Segundo Apellido', 
            data: 'usuario_ape2',
            render: (data, type, row, meta) => {
                return data && data !== null ? data : '-';
            }
        },
        { title: 'Correo', data: 'usuario_correo' },
        { title: 'Teléfono', data: 'usuario_tel' },
        { title: 'DPI', data: 'usuario_dpi' },
        { 
            title: 'Dirección', 
            data: 'usuario_direc',
            width: '15%'
        },
        {
            title: 'Situación',
            data: 'usuario_situacion',
            width: '8%',
            render: (data, type, row, meta) => {
                if (data == 1) {
                    return '<span class="badge bg-success">Activo</span>';
                } else {
                    return '<span class="badge bg-danger">Inactivo</span>';
                }
            }
        },
        {
            title: 'Acciones',
            data: 'usuario_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-nom1="${row.usuario_nom1}"  
                         data-nom2="${row.usuario_nom2 || ''}"  
                         data-ape1="${row.usuario_ape1}"  
                         data-ape2="${row.usuario_ape2 || ''}"  
                         data-tel="${row.usuario_tel}"  
                         data-direc="${row.usuario_direc}"  
                         data-dpi="${row.usuario_dpi}"  
                         data-correo="${row.usuario_correo}"  
                         data-situacion="${row.usuario_situacion}">
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-danger eliminar mx-1' 
                         data-id="${data}">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ],
})

const BuscarUsuarios = async () =>{
    const url = '/montes_final_comisiones_ingSoft1/usuarios';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config)
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo === 1) {
            
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: false,
                timer: 2000,
            });

            datatable.clear().draw();
            datatable.rows.add(data).draw();
            
        } else {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "Información",
                text: mensaje,
                showConfirmButton: false,
                timer: 3000,
            });
            return;
        }
    } catch (error) {
        console.log(error);
        
    }

}

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset

    document.getElementById('usuario_id').value = datos.id
    document.getElementById('usuario_nom1').value = datos.nom1
    document.getElementById('usuario_nom2').value = datos.nom2
    document.getElementById('usuario_ape1').value = datos.ape1
    document.getElementById('usuario_ape2').value = datos.ape2
    document.getElementById('usuario_tel').value = datos.tel
    document.getElementById('usuario_direc').value = datos.direc
    document.getElementById('usuario_dpi').value = datos.dpi
    document.getElementById('usuario_correo').value = datos.correo
    document.getElementById('usuario_situacion').value = datos.situacion

    // Limpiar campos de contraseña en modo edición
    document.getElementById('usuario_contra').value = ''
    document.getElementById('confirmar_contra').value = ''
    
    // Hacer campos de contraseña opcionales en edición
    document.getElementById('usuario_contra').removeAttribute('required')
    document.getElementById('confirmar_contra').removeAttribute('required')

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0
    })

}

const limpiarTodo = () => {
    FormUsuarios.reset();
    
    // Restaurar campos de contraseña como requeridos
    document.getElementById('usuario_contra').setAttribute('required', 'required')
    document.getElementById('confirmar_contra').setAttribute('required', 'required')
    
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');

}

const ModificarUsuario = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    // Validar solo campos visibles y requeridos
    const camposRequeridos = ['usuario_nom1', 'usuario_ape1', 'usuario_tel', 'usuario_direc', 'usuario_dpi', 'usuario_correo'];
    const camposVacios = camposRequeridos.filter(campo => {
        const elemento = document.getElementById(campo);
        return !elemento.value.trim();
    });

    if (camposVacios.length > 0) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe llenar todos los campos obligatorios",
            showConfirmButton: false,
            timer: 3000,
        });
        BtnModificar.disabled = false;
        return;        
    }

    // Validar contraseñas solo si se proporcionan
    const contrasena = document.getElementById('usuario_contra').value;
    const confirmarContrasena = document.getElementById('confirmar_contra').value;
    
    if (contrasena || confirmarContrasena) {
        if (contrasena !== confirmarContrasena) {
            Swal.fire({
                position: "center",
                icon: "warning",
                title: "CONTRASEÑAS NO COINCIDEN",
                text: "Las contraseñas deben ser iguales",
                showConfirmButton: false,
                timer: 3000,
            });
            BtnModificar.disabled = false;
            return;
        }
    }

    const body = new FormData(FormUsuarios);

    const url = '/montes_final_comisiones_ingSoft1/usuarios/modificar';
    const config = {
        method: 'POST',
        body
    }

    try {
        
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos

        if (codigo === 1) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: false,
                timer: 3000,
            });

            limpiarTodo();
            BuscarUsuarios();

        } else {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: false,
                timer: 3000,
            });
            return;
        }

    } catch (error) {
        console.log(error);
    }
    BtnModificar.disabled = false;
}

const EliminarUsuario = async (e) => {
    const idUsuario = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea ejecutar esta acción?",
        text: "Usted eliminará un usuario",
        showConfirmButton: true,
        confirmButtonText: "Sí, eliminar",
        confirmButtonColor: "#d33",
        cancelButtonText: "Cancelar",
        showCancelButton: true
    });

    if (!AlertaConfirmarEliminar.isConfirmed) return;

    // Preparamos el body para POST
    const body = new URLSearchParams();
    body.append('usuario_id', idUsuario);

    try {
        const respuesta = await fetch('/montes_final_comisiones_ingSoft1/usuarios/eliminar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body
        });

        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo === 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: false,
                timer: 2000
            });
            BuscarUsuarios();
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: false,
                timer: 3000
            });
        }

    } catch (error) {
        console.log(error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Error de conexión",
            showConfirmButton: false,
            timer: 3000
        });
    }
};


//Eventos
BuscarUsuarios(); // Cargar usuarios al iniciar
validarTelefono.addEventListener('change', validacionTelefono);
validarDpi.addEventListener('change', validacionDpi);

//guardar
FormUsuarios.addEventListener('submit', guardarUsuario)

//btn limpiar
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarUsuario);

//datatable
datatable.on('click', '.eliminar', EliminarUsuario);
datatable.on('click', '.modificar', llenarFormulario);