import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario, Toast } from "../funciones";
import { lenguaje } from "../lenguaje";

const formUsuario = document.getElementById('formUsuario');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscar = document.getElementById('BtnBuscar');
const validarTelefono = document.getElementById('usuario_tel');
const validarDpi = document.getElementById('usuario_dpi');

// Validación de teléfono
const validacionTelefono = () => {
    const cantidadDigitos = validarTelefono.value;

    if (cantidadDigitos.length < 1) {
        validarTelefono.classList.remove('is-valid', 'is-invalid');
    } else {
        if (cantidadDigitos.length !== 8) {
            Swal.fire({
                position: "center",
                icon: "warning",
                title: "Revise el número de teléfono",
                text: "Debe tener exactamente 8 dígitos",
                showConfirmButton: false,
                timer: 3000
            });
            validarTelefono.classList.remove('is-valid');
            validarTelefono.classList.add('is-invalid');
        } else {
            validarTelefono.classList.remove('is-invalid');
            validarTelefono.classList.add('is-valid');
        }
    }
}

// Validación de DPI
const validacionDpi = () => {
    const cantidadDigitos = validarDpi.value;

    if (cantidadDigitos.length < 1) {
        validarDpi.classList.remove('is-valid', 'is-invalid');
    } else {
        if (cantidadDigitos.length !== 13) {
            Swal.fire({
                position: "center",
                icon: "warning",
                title: "Revise el número de DPI",
                text: "Debe tener exactamente 13 dígitos",
                showConfirmButton: false,
                timer: 3000
            });
            validarDpi.classList.remove('is-valid');
            validarDpi.classList.add('is-invalid');
        } else {
            validarDpi.classList.remove('is-invalid');
            validarDpi.classList.add('is-valid');
        }
    }
}

// Función para guardar usuario
const guardarUsuario = async (e) => {
    e.preventDefault();
    
    // Validar contraseñas
    const password = document.getElementById('usuario_contra').value;
    const confirmPassword = document.getElementById('confirmar_contra').value;
    
    if (password !== confirmPassword) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Las contraseñas no coinciden'
        });
        return;
    }
    
    try {
        const body = new FormData(formUsuario);
        const url = "/montes_final_comisiones_ingSoft1/usuarios/guardar";
        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje } = data;

        let icon = 'info';
        if (codigo == 1) {
            icon = 'success';
            formUsuario.reset();
            // Ocultar la tabla después de guardar
            document.getElementById('seccionTabla').classList.add('d-none');
        } else if (codigo == 2) {
            icon = 'warning';
        } else if (codigo == 0) {
            icon = 'error';
        }

        Toast.fire({
            icon,
            title: mensaje
        });

    } catch (error) {
        console.log(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión con el servidor'
        });
    }
}

// Configuración del DataTable
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
            width: '8%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                if (data && data !== null && data !== '') {
                    return `<img src="/montes_final_comisiones_ingSoft1/public/${data}" alt="Foto usuario" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjUwIiBoZWlnaHQ9IjUwIiBmaWxsPSIjNjY2NjY2Ii8+CjxwYXRoIGQ9Ik0yNSAyM0MyNy43NjE0IDIzIDMwIDIwLjc2MTQgMzAgMThDMzAgMTUuMjM4NiAyNy43NjE0IDEzIDI1IDEzQzIyLjIzODYgMTMgMjAgMTUuMjM4NiAyMCAxOEMyMCAyMC43NjE0IDIyLjIzODYgMjMgMjUgMjNaIiBmaWxsPSJ3aGl0ZSIvPgo8cGF0aCBkPSJNMjUgMjVDMjAuMDI5NCAyNSAxNiAyOS4wMjk0IDE2IDM0VjM3SDM0VjM0QzM0IDI5LjAyOTQgMjkuOTcwNiAyNSAyNSAyNVoiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=';">`;
                } else {
                    return `<div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-person text-white"></i>
                            </div>`;
                }
            }
        },
        { title: 'Primer Nombre', data: 'usuario_nom1', width: '10%' },
        { title: 'Segundo Nombre', data: 'usuario_nom2', width: '10%' },
        { title: 'Primer Apellido', data: 'usuario_ape1', width: '10%' },
        { title: 'Segundo Apellido', data: 'usuario_ape2', width: '10%' },
        { title: 'Correo', data: 'usuario_correo', width: '12%' },
        { title: 'Teléfono', data: 'usuario_tel', width: '8%' },
        { title: 'DPI', data: 'usuario_dpi', width: '10%' },
        { 
            title: 'Dirección', 
            data: 'usuario_direc', 
            width: '12%',
            render: (data, type, row, meta) => {
                if (data && data.length > 30) {
                    return data.substring(0, 30) + '...';
                }
                return data || '-';
            }
        },
        { 
            title: 'Fecha Contratación', 
            data: 'usuario_fecha_contra',
            width: '10%',
            render: (data, type, row, meta) => {
                if (data && data !== null) {
                    return new Date(data).toLocaleDateString('es-GT');
                }
                return '-';
            }
        },
        { 
            title: 'Estado', 
            data: 'usuario_situacion',
            width: '8%',
            render: (data, type, row, meta) => {
                if (data == 1) {
                    return `<span class="badge bg-success">Activo</span>`;
                } else {
                    return `<span class="badge bg-danger">Inactivo</span>`;
                }
            }
        },
        {
            title: 'Acciones',
            data: 'usuario_id',
            width: '15%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center flex-wrap gap-1'>
                     <button class='btn btn-warning btn-sm modificar' 
                         data-id="${data}" 
                         data-nom1="${row.usuario_nom1}"  
                         data-nom2="${row.usuario_nom2}"  
                         data-ape1="${row.usuario_ape1}"  
                         data-ape2="${row.usuario_ape2}"  
                         data-tel="${row.usuario_tel}"  
                         data-direc="${row.usuario_direc}"  
                         data-dpi="${row.usuario_dpi}"  
                         data-correo="${row.usuario_correo}"
                         data-fecha="${row.usuario_fecha_contra}"
                         data-situacion="${row.usuario_situacion}"
                         title="Modificar usuario">
                         <i class='bi bi-pencil-square'></i>
                     </button>
                     <button class='btn btn-danger btn-sm eliminar' 
                         data-id="${data}"
                         title="Eliminar usuario">
                        <i class="bi bi-trash3"></i>
                     </button>
                 </div>`;
            }
        }
    ],
});

// Función para buscar usuarios
const BuscarUsuario = async () => {
    const url = '/montes_final_comisiones_ingSoft1/usuarios/buscar';
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo === 1) {
            // Mostrar la sección de la tabla
            document.getElementById('seccionTabla').classList.remove('d-none');
            
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
        }
    } catch (error) {
        console.log(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión con el servidor'
        });
    }
}

// Función para llenar el formulario al modificar
const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('usuario_id').value = datos.id;
    document.getElementById('usuario_nom1').value = datos.nom1;
    document.getElementById('usuario_nom2').value = datos.nom2;
    document.getElementById('usuario_ape1').value = datos.ape1;
    document.getElementById('usuario_ape2').value = datos.ape2;
    document.getElementById('usuario_tel').value = datos.tel;
    document.getElementById('usuario_direc').value = datos.direc;
    document.getElementById('usuario_dpi').value = datos.dpi;
    document.getElementById('usuario_correo').value = datos.correo;
    document.getElementById('usuario_fecha_contra').value = datos.fecha;
    document.getElementById('usuario_situacion').value = datos.situacion;

    // Ocultar campos de contraseña en modo edición
    const seccionAcceso = document.getElementById('seccion-acceso');
    const camposPassword = document.getElementById('campos-password');
    
    if (seccionAcceso) seccionAcceso.style.display = 'none';
    if (camposPassword) camposPassword.style.display = 'none';
    
    // Hacer contraseñas no requeridas durante edición
    document.getElementById('usuario_contra').required = false;
    document.getElementById('confirmar_contra').required = false;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Función para limpiar formulario
const limpiarTodo = () => {
    formUsuario.reset();
    
    // Mostrar campos de contraseña nuevamente
    const seccionAcceso = document.getElementById('seccion-acceso');
    const camposPassword = document.getElementById('campos-password');
    
    if (seccionAcceso) seccionAcceso.style.display = 'block';
    if (camposPassword) camposPassword.style.display = 'block';
    
    // Hacer contraseñas requeridas nuevamente
    document.getElementById('usuario_contra').required = true;
    document.getElementById('confirmar_contra').required = true;
    
    // Limpiar validaciones visuales
    const inputs = formUsuario.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });
    
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

// Función para modificar usuario
const ModificarUsuario = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    // Validar formulario excluyendo campos de contraseña
    if (!validarFormulario(formUsuario, ['usuario_contra', 'confirmar_contra'])) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos obligatorios",
            showConfirmButton: false,
            timer: 3000,
        });
        BtnModificar.disabled = false;
        return;        
    }

    const body = new FormData(formUsuario);
    const url = '/montes_final_comisiones_ingSoft1/usuarios/modificar';
    const config = {
        method: 'POST',
        body
    };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo === 1) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: false,
                timer: 2000,
            });

            limpiarTodo();
            BuscarUsuario();

        } else {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: false,
                timer: 3000,
            });
        }

    } catch (error) {
        console.log(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión con el servidor'
        });
    }
    BtnModificar.disabled = false;
}

// Función para eliminar usuario
const EliminarUsuario = async (e) => {
    const idUsuario = e.currentTarget.dataset.id;

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Confirmar eliminación?",
        text: "Esta acción marcará al usuario como inactivo",
        showConfirmButton: true,
        confirmButtonText: "Sí, eliminar",
        confirmButtonColor: "#d33",
        cancelButtonText: "Cancelar",
        showCancelButton: true
    });

    if (!AlertaConfirmarEliminar.isConfirmed) return;

    // Preparar el body para POST
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
            BuscarUsuario();
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
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión con el servidor'
        });
    }
};

//Eventos
// BuscarUsuario(); // Comentamos esta línea para que no se ejecute automáticamente
validarTelefono.addEventListener('change', validacionTelefono);
validarDpi.addEventListener('change', validacionDpi);

//guardar
formUsuario.addEventListener('submit', guardarUsuario)

//btn limpiar
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarUsuario);
BtnBuscar.addEventListener('click', BuscarUsuario);

//datatable
datatable.on('click', '.eliminar', EliminarUsuario);
datatable.on('click', '.modificar', llenarFormulario);