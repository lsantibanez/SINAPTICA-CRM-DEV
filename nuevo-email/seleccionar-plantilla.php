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
    <link href="/css/global/estilos.css" rel="stylesheet">
    <style>
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .card {
            width: 100%;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
        }

        .card-footer {
            padding: 10px;
            background-color: #f7f7f7;
            border-top: 1px solid #ddd;
        }

        .card-footer code {
            font-size: 12px;
        }

        .btn {
            font-size: 12px;
        }

        form, a {
            display: inline-block;
            margin: 0;
        }


        @media (max-width: 768px) {
            .col-md-6 {
                flex: 0 0 100%;
            }
        }

        @media (min-width: 992px) {
            .col-md-6 {
                flex: 0 0 48%;
            }
        }

        @media (min-width: 1200px) {
            .col-lg-4 {
                flex: 0 0 30%;
            }
        }

    </style>
</head>
<body>
<div id="container" class="effect mainnav-lg">
    <?php include '../layout/header.php'; ?>
    <div class="boxed">
        <div id="content-container">
            <div id="page-title">
                <h1 class="page-header text-overflow">Seleccionar Campañas</h1>
            </div><!-- page title -->
            <ol class="breadcrumb">
                <li><a href="#">Email</a></li>
                <li class="active">Seleccionar Campaña</li>
            </ol><!-- breadcrumb -->
            <div id="page-content">
                <div id="appConsultaTemplates">
                    <!--Tabla -->
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row" v-if="selectedTemplate">
                                        <div class="col-md-12 items-center">
                                                <h4>Resumen de la Campaña</h4>
                                            <div>
                                                <p><strong>Nombre de la campaña:</strong> {{ campaign.name }}
                                                </p>
                                                <p><strong>Template seleccionado:</strong>
                                                    {{ selectedTemplate.name }}</p>
                                                <img :src="selectedTemplate.urlPreview" class="img-fluid mt-3"
                                                     alt="Vista previa">
                                            </div>
                                            <button class="btn btn-primary my-4" @click="unselectTemplate">
                                                Cambiar Template
                                            </button>
                                        </div>
                                        <div class="row">
                                        //TODO: Mostrar la plantilla con la información del excel.
                                        </div>
                                    </div>
                                    <div v-else>
                                        <!--                                    Cabecera-->
                                        <div class="d-flex justify-between items-baseline mb-3">
                                            <h3>Variables disponibles desde el archivo Excel</h3>
                                            <a href="/nuevo-email/crear-plantilla" class="btn btn-success"><i
                                                        class="fa-solid fa-plus"></i>
                                                Crear Plantilla</a>
                                        </div>

                                        <div v-if="loading" class="text-center mt-5">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                        </div>

                                        <div class="row row-cols-auto">
                                            <div class="col mb-3" v-for="(item, index) in customVariables" :key="index">
                                                <span class="badge bg-primary p-3 text-lg ">{{ item }}</span>
                                            </div>
                                        </div>

                                        <div class="my-4">
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="Buscar plantilla por nombre..."
                                                    v-model="searchQuery"
                                                    @input="searchTemplates"
                                            >
                                        </div>

                                        <div class="container-fluid text-center mt-30">
                                            <div class="row">
                                                <div class="col-md-6 col-lg-6"
                                                     v-for="(template, index) in paginatedItems"
                                                     :key="index">

                                                    <div>
                                                        <div class="card">
                                                            <div class="card-header p-3 ">
                                                                <h4>{{ template.name }}</h4>
                                                            </div>

                                                            <div class="card-body text-center">
                                                                <img :src="template.urlPreview" class="card-img-top"
                                                                     alt="Vista previa"
                                                                     @click="openModal(template.urlPreview)"
                                                                     style="cursor: pointer;">
                                                            </div>
                                                            <!-- Pie del panel -->
                                                            <div class="card-footer">
                                                                <div class="d-flex justify-center gap-10 mt-3">
                                                                    <form @submit="selectTemplate(template.id, $event)"
                                                                          class="m-0">
                                                                        <button type="submit"
                                                                                class="btn btn-success btn-sm">
                                                                            <i class="glyphicon glyphicon-ok"></i>
                                                                            Seleccionar
                                                                        </button>
                                                                    </form>
                                                                    <form @submit.prevent="doubleTemplate(template.id)"
                                                                          class="m-0">
                                                                        <button type="submit" class="btn btn-info btn-sm">
                                                                            <i class="glyphicon glyphicon-duplicate"></i>
                                                                        </button>
                                                                    </form>
                                                                    <a :href="'/nuevo-email/editar-plantilla?id='+template.id"
                                                                       class="btn btn-warning btn-sm">
                                                                        <i class="glyphicon glyphicon-pencil"></i>
                                                                    </a>
                                                                    <form @submit="deleteTemplate(template.id, $event)"
                                                                          class="m-0">
                                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                                            <i class="glyphicon glyphicon-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--                                    Paginación-->
                                        <nav>
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                                    <a class="page-link" href="#"
                                                       @click.prevent="changePage(currentPage - 1)">Anterior</a>
                                                </li>
                                                <li class="page-item" v-for="page in totalPages" :key="page"
                                                    :class="{ active: currentPage === page }">
                                                    <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page
                                                        }}</a>
                                                </li>
                                                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                                    <a class="page-link" href="#"
                                                       @click.prevent="changePage(currentPage + 1)">Siguiente</a>
                                                </li>
                                            </ul>
                                        </nav>

                                        <!--                                    Modal-->
                                        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog"
                                             aria-labelledby="imageModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Cerrar">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" id="imageModalLabel">Vista Previa de la
                                                            Imagen</h4>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img id="modalImage" src="" alt="Vista Previa Ampliada"
                                                             class="img-responsive">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--                                    End modal-->
                                    </div>

                                </div>
                            </div>
                        </div>
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
<script>
    var app = new Vue({
        el: '#appConsultaTemplates',
        data: {
            id: '',
            campaign: {
                name: '',
                date: 0,
                subject: '',
                template_id: '',
                sender: '',
                emailResponse: '',
                unsubcribe: '',
            },
            templatesWithImages: [],
            selectedTemplate: null,
            customVariables: [],
            templates: [],
            filteredTemplates: [],
            currentPage: 1,
            itemsPerPage: 4,
            loading: true,
            searchQuery: '',
        },
        mounted() {
            const params = new URLSearchParams(window.location.search);
            this.id = params.get('id');

            this.fetchCustomVariables(this.id);
            this.getTemplates();
            this.fetchCampaignData(this.id);
        },
        computed: {
            totalPages() {
                return Math.ceil(this.templates.length / this.itemsPerPage);
            },
            paginatedItems() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredTemplates.slice(start, end);
            }
        },
        methods: {
            async fetchCampaignData(campaignId) {
                this.loading = true;
                axios.get(`/includes/campaigns/getCampaign?id=${campaignId}`)
                    .then(response => {
                        this.campaign = response.data.item;
                        if(this.campaign.template_id){
                            axios.get(`/includes/templates/getTemplate?id=${this.campaign.template_id}`)
                                .then(response => {
                                    this.selectedTemplate = response.data.item;
                                })
                                .catch(error => {
                                    if (error.response) {
                                        toastr.error(error.response.data.message || 'Ocurrió un error al procesar la solicitud.');
                                    } else {
                                        toastr.error('Error de conexión con el servidor.');
                                    }
                                })
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
            },
            fetchCustomVariables(campaignId) {
                this.loading = true;
                axios.get(`/includes/campaigns/getCustomVariables?id=${campaignId}`)
                    .then(response => {
                        if (Array.isArray(response.data.items)) {
                            this.customVariables = response.data.items;
                        } else {
                            console.warn('La respuesta no es un arreglo:', response.data.items);
                            this.customVariables = [];
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar campaña:', error);
                        this.customVariables = [];
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            searchTemplates() {
                this.currentPage = 1;
                this.getTemplates();
            },
            getTemplates() {
                const params = {
                    search: this.searchQuery,
                };
                this.loading = true;
                axios.get('/includes/templates/getTemplates', {params})
                    .then(response => {
                        this.templates = response.data.items;
                        this.filteredTemplates = this.templates;
                    })
                    .catch(error => {
                        console.error('Error al cargar datos:', error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            changePage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                }
            },
            handlePageClick(pageNum) {
                this.currentPage = pageNum;
            },
            openModal(imageUrl) {
                const modalImage = document.getElementById('modalImage');
                modalImage.src = imageUrl;

                $('#imageModal').modal('show');
            },
            selectTemplate(templateId, event) {
                event.preventDefault();
                this.loading = true;
                const data = {
                    campaignId: this.id,
                };

                axios.post(`/includes/templates/selectTemplate?id=${templateId}`, data)
                    .then(response => {
                        if (response.data.success) {
                            toastr.success(response.data.message);
                            this.selectedTemplate = response.data.item;

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
            deleteTemplate(templateId) {
                if (!confirm('¿Estás seguro de que deseas eliminar esta plantilla?')) {
                    return;
                }

                this.loading = true;
                axios.get(`/includes/new_email/deleteTemplate`, {
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
            },
            unselectTemplate() {
                axios.get(`/includes/templates/unselectTemplate?id=${this.campaign.id}`)
                    .then(response => {
                        if(response.data.success){
                            this.selectedTemplate = null;
                        }
                    })
                    .catch(error => {
                        if (error.response) {
                            toastr.error(error.response.data.message || 'Ocurrió un error al procesar la solicitud.');
                        } else {
                            toastr.error('Error de conexión con el servidor.');
                        }
                    })
            },
        },

    });
</script>
</body>
</html>