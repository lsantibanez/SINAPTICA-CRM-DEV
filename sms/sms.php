<?PHP
error_reporting(E_ALL & ~E_NOTICE);

require_once('../class/db/DB.php');
$db = new DB();
require_once('../class/session/session.php');
include("../class/global/global.php");
$objetoSession = new Session('1,2,3,4,5,6', false);
$objetoSession->crearVariableSession($array = array("idMenu" => "constas,constasrut"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = (int)$_SESSION['cedente'];
$nombreUsuario = $_SESSION['nombreUsuario'];
$idUsuarioLogin = $_SESSION['id_usuario'];
$nombreProyecto = $_SESSION['nombreCedente'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Sinaptica | Software de Estrategia | Consultas</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/nifty.min.css" rel="stylesheet">
    <link href="/premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="/plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <link href="/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/plugins/animate-css/animate.min.css" rel="stylesheet">
    <link href="/plugins/switchery/switchery.min.css" rel="stylesheet">
    <link href="/plugins/morris-js/morris.min.css" rel="stylesheet">
    <link href="/css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="/plugins/pace/pace.min.css" rel="stylesheet">
    <link href="/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="/plugins/datatables/media/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
    <link href="/plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="/css/extra/flatpickr.min.css">
    <link rel="stylesheet" href="/css/extra/toastr.min.css">
    <link href="/css/global/global.css" rel="stylesheet">
    <style>
        .template-image {
            max-width: 150px;
            max-height: 100px;
            object-fit: contain;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div id="container" class="effect mainnav-lg">
    <?php include '../layout/header.php'; ?>
    <div class="boxed">
        <div id="content-container">
            <div id="page-title">
                <h1 class="page-header text-overflow">Plantillas</h1>
            </div><!-- page title -->
            <ol class="breadcrumb">
                <li><a href="#">Email</a></li>
                <li class="active">Plantillas</li>
            </ol><!-- breadcrumb -->
            <div id="page-content">
                <div id="appConsultaTemplates">
                    <!-- Spinner -->
                    <div v-if="loading" class="text-center mt-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                    <!-- Fin Spinner -->
                    <!--Tabla -->
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <h4>Listado de Plantillas</h4>
                                    <a class="btn btn-success" href="crear-plantilla"> <i class="fa-solid fa-plus"></i>
                                        Crear Plantilla</a>
                                    <table class="table table-sm">
                                        <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th style="width: 5%; text-align: center;">Disponible</th>
                                            <th style="width: 15%; text-align: center;">Vista Previa</th>
                                            <th style="width: 15%; text-align: center;">Creado el</th>
                                            <th style="width: 10%; text-align: center;">Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-if="!templates.length">
                                            <td colspan="5">No hay datos.</td>
                                        </tr>
                                        <tr v-for="template in paginatedTemplates" :key="template.id">
                                            <td>{{ template.name }}</td>
                                            <td>
                                                <div class="form-check form-switch">

                                                    <input :checked="isEnable(template.enable)"
                                                           class="form-check-input"
                                                           type="checkbox"
                                                           role="switch"
                                                           id="flexSwitchCheckDefault"
                                                           @change="updateTemplateState(template)"/>
                                                </div>
                                            </td>
                                            <td>
                                                <img :src="template.urlPreview" alt="Vista Previa"
                                                     class="template-image" @click="openModal(template.urlPreview)">
                                            </td>
                                            <td>{{ formatDateToCustomFormat(template.created_at) }}</td>
                                            <td>
                                                <div class="btn-group">

                                                    <div class="btn-group btn-group-justified">
                                                        <a type="submit" class="btn btn-primary"
                                                           @click="doubleTemplate(template.id)" data-toggle="tooltip"
                                                           data-placement="top" title="Duplicar">
                                                            <i class="fa-regular fa-copy"></i>
                                                        </a>
                                                        <a class="btn btn-info"
                                                           :href="'/nuevo-email/editar-plantilla?id='+template.id"
                                                           data-toggle="tooltip" data-placement="top" title="Editar">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                                        <a class="btn btn-danger"
                                                           data-toggle="tooltip" data-placement="top" title="Eliminar" @click="deleteTemplate(template.id)">
                                                            <i class="fa-regular fa-trash-can"></i>
                                                        </a>

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <!-- Controles de paginación -->
                                    <nav>
                                        <ul class="pagination">
                                            <li :class="{ disabled: currentPage === 1 }">
                                                <a href="#" @click.prevent="changePage(currentPage - 1)">&laquo;</a>
                                            </li>
                                            <li v-for="page in totalPages" :class="{ active: page === currentPage }">
                                                <a href="#" @click.prevent="changePage(page)">{{ page }}</a>
                                            </li>
                                            <li :class="{ disabled: currentPage === totalPages }">
                                                <a href="#" @click.prevent="changePage(currentPage + 1)">&raquo;</a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <!-- Fin de controles de paginación -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Fin Tabla-->

                    <!--Modal-->
                    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog"
                         aria-labelledby="imageModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="imageModalLabel">Vista Previa de la Imagen</h4>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="modalImage" src="" alt="Vista Previa Ampliada" class="img-responsive">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Fin Modal-->
                </div>
            </div><!-- page content --->
        </div><!-- page container -->
        <?php include '../layout/main-menu.php'; ?>
    </div><!-- boxed -->
    <footer id="footer">
        <div class="show-fixed pull-right">
            <ul class="footer-list list-inline">

            </ul>
        </div>
    </footer>
    <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
</div>
<script src="/js/jquery-2.2.1.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="/js/nifty.min.js"></script>
<script src="/js/demo/nifty-demo.min.js"></script>
<script src="/plugins/pace/pace.min.js"></script>
<script src="/plugins/bootbox/bootbox.min.js"></script>
<script src="/js/global/funciones-global.js"></script>
<script src="/js/extra/vue.min.js"></script>
<script src="/js/extra/axios.min.js"></script>
<script src="/js/extra/flatpickr.js"></script>
<script src="/js/extra/toastr.min.js"></script>
<script src="/js/extra/vuejs-paginate@latest.js"></script>
<script src="/js/extra/dayjs/dayjs.min.js"></script>
<script src="/js/extra/dayjs/relativeTime.js"></script>
<script src="/js/extra/dayjs/es.js"></script>


<script>
    var app = new Vue({
        el: '#appConsultaTemplates',
        data: {
            templates: [],
            paginatedTemplates: [],
            currentPage: 1,
            pageSize: 4,
            totalPages: 0,
            loading: true,
        },
        mounted() {
            this.getTemplates();
            dayjs.extend(dayjs_plugin_relativeTime);
            dayjs.locale('es');
        },
        methods: {
            getSms() {
                this.loading = true;
                axios.get('/includes/sms/getSms')
                    .then(response => {
                        this.templates = response.data.items;
                        this.setupPagination();
                    })
                    .catch(error => {
                        console.error('Error al cargar datos:', error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            formatDateToHumanReadable(date) {
                console.log(date);
                return dayjs(date).fromNow();
            },
            formatDateToCustomFormat(date) {
                return dayjs(date).format('');
            },
            isEnable(enable){
                if(enable === "1" ){

                    return true;
                }else{
                    return false;
                }
            },
            setupPagination() {
                this.totalPages = Math.ceil(this.templates.length / this.pageSize);
                this.updatePage();
            },
            updatePage() {
                const start = (this.currentPage - 1) * this.pageSize;
                const end = start + this.pageSize;
                this.paginatedTemplates = this.templates.slice(start, end);
            },
            changePage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                    this.updatePage();
                }
            },
            doubleTemplate(templateId) {
                this.loading = true;
                axios.get(`/includes/templates/doubleTemplate`, {
                    params: {id: templateId}
                })
                    .then(response => {
                        if (response.data.success) {
                            toastr.success(response.data.message);
                        } else {
                            toastr.warning(response.data.message);
                        }
                        this.getTemplates();
                    })
                    .catch(error => {
                        if (error.response) {
                            toastr.error(error.response.data.message || 'Ocurrió un error al procesar la solicitud.');
                        } else {
                            toastr.error('Error de conexión con el servidor.');
                        }
                    })
                    .finally(() => {
                        this.loading = false;
                    })
            },
            deleteTemplate(templateId){
                if (!confirm('¿Estás seguro de que deseas eliminar esta plantilla?')) {
                    return;
                }

                this.loading = true;
                axios.get(`/includes/new_email/deleteTemplate`,{
                    params: {id: templateId}
                })
                    .then(response => {
                        if (response.data.success) {
                            toastr.success(response.data.message);
                            this.getTemplates();
                        } else {
                            toastr.warning(response.data.message);
                        }

                    })
                    .catch(error => {
                        if (error.response) {
                            toastr.error(error.response.data.message || 'Ocurrió un error al procesar la solicitud.');
                        } else {
                            toastr.error('Error de conexión con el servidor.');
                        }
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }

        },
    });
</script>
</body>
</html>