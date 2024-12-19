<?PHP
error_reporting(E_ALL & ~E_NOTICE);

require_once('../class/db/DB.php');
$db = new DB();
require_once('../class/session/session.php');
include("../class/global/global.php");
$objetoSession = new Session('1,2,3,4,5,6',false);
$objetoSession->crearVariableSession($array = array("idMenu" => "emails,campañas"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")) {
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = (int) $_SESSION['cedente'];
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
    <link href="/plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
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
                flex: 0 0 50%;
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
    <?php include '../layout/header.php';  ?>
    <div class="boxed">
        <div id="content-container">
            <div id="page-title">
                <h1 class="page-header text-overflow">Campañas</h1>
            </div><!-- page title -->
            <ol class="breadcrumb">
                <li><a href="#">Email</a></li>
                <li class="active">Campañas</li>
            </ol><!-- breadcrumb -->
            <div id="page-content">
                <div id="appConsultaTemplates">
                    <!--Tabla -->
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <h4>Crear Campañas</h4>
                                    <!--Paso 1-->
                                    <form @submit.prevent="fillCampaign" v-if="step === 1" novalidate>
                                        <div class="row">

                                            <div class="col-md-8">
                                                <div class="mb-4">
                                                    <label for="name" class="form-label">Nombre de la Campaña</label>
                                                    <input type="text" id="name" class="form-control" v-model="campaign.step1.name" required/>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-4">
                                                    <label for="programmed" class="form-label">Envío Programado</label>
                                                    <select class="form-control mb-0" v-model="programmed" id="programmed">
                                                        <option selected value="0">No</option>
                                                        <option value="1">Si</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div :class="programmed === '1' ? 'col-md-8':'col-md-12'">
                                                <div class="mb-4">
                                                    <label for="subject" class="form-label">Asunto:</label>
                                                    <input type="text" id="subject" class="form-control" v-model="campaign.step1.subject" required/>
                                                </div>
                                            </div>

                                            <div class="col-md-4" v-if="programmed === '1'">
                                                <div class="mb-4">
                                                    <label for="date" class="form-label">Fecha de envío:</label>
                                                    <input type="datetime-local" :min="minDateTime"  id="date" class="form-control" style="line-height: 16px !important;" v-model="campaign.step1.date" required />
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-4">
                                                    <label for="excelFile" class="form-label">Carga de archivos base (Excel):</label>
                                                    <input type="file" id="excelFile" class="form-control" @change="handleFileUpload" accept=".xls,.xlsx" />
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label for="sender" class="form-label">Nombre remitente:</label>
                                                    <input type="text" id="sender" class="form-control" v-model="campaign.step1.sender" required/>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label for="emailResponse" class="form-label">Correo de respuesta:</label>
                                                    <input type="email" id="emailResponse" class="form-control" v-model="campaign.step1.emailResponse"
                                                           required/>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-4">
                                                    <label for="unsubcribe" class="form-label">Url desuscribir:</label>
                                                    <input type="url" id="unsubcribe" class="form-control" v-model="campaign.step1.unsubcribe"
                                                           required/>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="d-flex justify-content-between align-items-center my-4 gap-10">
                                            <button :disabled="loading"  class="btn btn-primary">
                                                {{!loading ? 'Continuar' : 'Cargando...'}}
                                            </button>
                                            <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                                            <a class="btn btn-warning" href="/nuevo-email/campaña">Regresar</a>
                                        </div>

                                        <div v-if="loading" class="text-center mt-5">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                        </div>
                                    </form>
                                    <!--Fin de Paso 1-->

                                    <!--Paso 2-->
                                    <div v-if="!campaign.step2.selectedTemplate && step === 2">
                                        <!--                                    Cabecera-->
                                        <!--                                        <div class="d-flex justify-between items-baseline mb-3">-->
                                        <!--                                            <h3>Variables disponibles desde el archivo Excel</h3>-->
                                        <!--                                            <a href="/nuevo-email/crear-plantilla" class="btn btn-success"><i-->
                                        <!--                                                        class="fa-solid fa-plus"></i>-->
                                        <!--                                                Crear Plantilla</a>-->
                                        <!--                                        </div>-->

                                        <div v-if="loading" class="text-center mt-5">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                        </div>

                                        <div class="row row-cols-auto gap-5">
                                            <div class="col mb-3" v-for="(item, index) in campaign.step2.customVariables" :key="index">
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
                                                                        <button type="submit"
                                                                                class="btn btn-info btn-sm">
                                                                            <i class="glyphicon glyphicon-duplicate"></i>
                                                                        </button>
                                                                    </form>
                                                                    <a :href="'/nuevo-email/editar-plantilla?id='+template.id"
                                                                       class="btn btn-warning btn-sm">
                                                                        <i class="glyphicon glyphicon-pencil"></i>
                                                                    </a>
                                                                    <form @submit="deleteTemplate(template.id, $event)"
                                                                          class="m-0">
                                                                        <button type="submit"
                                                                                class="btn btn-danger btn-sm">
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
                                                    <a class="page-link" href="#"
                                                       @click.prevent="changePage(page)">{{ page
                                                        }}</a>
                                                </li>
                                                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                                    <a class="page-link" href="#"
                                                       @click.prevent="changePage(currentPage + 1)">Siguiente</a>
                                                </li>
                                            </ul>
                                            <button class="btn btn-warning" @click="changeStep(1)" >Volver</button>
                                        </nav>

                                        <!--                                    Modal-->
                                        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog"
                                             aria-labelledby="imageModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
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
                                    <!--Fin de Paso 2-->

                                    <!--Paso 3-->
                                    <div class="row" v-if="campaign.step2.selectedTemplate && step === 3">
                                        <div class="col-md-9 text-center">
                                            <h4>Resumen de la Campaña</h4>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-warning btn-block my-4"
                                                    @click="unselectTemplate">
                                                Cambiar Template
                                            </button>
                                        </div>
                                        <div class="col-md-12">
                                            <p><strong>Nombre de la campaña:</strong> {{ campaign.step1.name }}</p>
                                            <p><strong>Plantilla seleccionada:</strong> {{ campaign.step2.selectedTemplate.name }}
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                                <select class="form-control" v-model="campaign.step2.select_template" id="select_template">
                                                    <option value="" selected disabled>Seleccione los datos de prueba
                                                    </option>
                                                    <option v-for="data_email in campaign.step2.data_emails" :key="data_email.IDENTIFICADOR"
                                                            :value="data_email.IDENTIFICADOR">
                                                        {{ data_email.IDENTIFICADOR }} - {{ data_email.NOMBRE }}
                                                    </option>
                                                </select>
                                        </div>
                                        <div class="col-md-6">
                                            <button class="btn btn-info btn-block my-4" @click="fetchSelectTemplate">
                                                Ver Plantilla
                                            </button>
                                        </div>
                                        <div class="col-md-12" id="template_content">
                                        </div>

                                        <button :disabled="end_step" class="btn btn-success" @click="submitForm">Guardar Campaña</button>
                                    </div>
                                    <!--Fin de Paso 3-->
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
    <script src="/js/extra/html2canvas.min.js"></script>
    <script>
        var app = new Vue({
            el: '#appConsultaTemplates',
            data: {
                programmed : 0,
                campaign: {
                    step1: {
                        name: '',
                        date: '',
                        subject: '',
                        sender: '',
                        emailResponse: '',
                        unsubcribe: '',
                    },
                    step2: {
                        data_emails: [],
                        select_template: '',
                        json_content_template: '',
                        templatesWithImages: [],
                        selectedTemplate: null,
                        customVariables: [],
                    }
                },
                step: 1,
                end_step: false,
                templates: [],
                filteredTemplates: [],
                excelFile: null,
                excelValidated: false,
                excelPreview: [],
                currentPage: 1,
                itemsPerPage: 4,
                searchQuery: '',
                loading: false,
            },
            mounted() {
                this.getTemplates();
            },
            computed: {
                totalPages() {
                    return Math.ceil(this.templates.length / this.itemsPerPage);
                },
                paginatedItems() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return this.filteredTemplates.slice(start, end);
                },
                minDateTime() {
                    const now = new Date();
                    const year = now.getFullYear();
                    const month = String(now.getMonth() + 1).padStart(2, '0');
                    const day = String(now.getDate()).padStart(2, '0');
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');

                    return `${year}-${month}-${day}T${hours}:${minutes}`;
                },
            },
            methods: {
                // Global
                changeStep(step){
                    this.step = step;
                },
                // Step 1
                handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.excelFile = file;
                        this.validateExcel(file);
                    } else {
                        this.excelFile = null;
                        this.excelValidated = false;
                        this.excelPreview = [];
                    }
                },
                async validateExcel(file) {
                    this.loading = true;

                    const formData = new FormData();
                    formData.append("file", file);

                    try {
                        const response = await axios.post("/includes/campaigns/validateExcelCampaign", formData, {
                            headers: { "Content-Type": "multipart/form-data" },
                        });

                        if (response.data.success) {
                            this.excelValidated = true;
                            this.excelPreview = response.data.preview;
                            this.campaign.step2.customVariables = response.data.foundHeaders;
                            toastr.success(response.data.message);
                        } else {
                            this.excelValidated = false;
                            this.excelPreview = [];
                            toastr.warning(response.data.message || "Archivo no válido");
                        }
                    } catch (error) {
                        this.excelValidated = false;
                        this.excelPreview = [];
                        toastr.warning("Ocurrió un error al procesar la solicitud.");
                    } finally {
                        this.loading = false;
                    }
                },
                fillCampaign() {

                    if (!this.campaign.step1.name) {
                        toastr.warning("El nombre de la campaña es obligatorio.");
                        return;
                    }

                    if (!this.campaign.step1.subject) {
                        toastr.warning("El asunto de la campaña es obligatorio.");
                        return;
                    }

                    if (!this.campaign.step1.sender) {
                        toastr.warning("El nombre del remitente es obligatorio.");
                        return;
                    }

                    if (!this.campaign.step1.emailResponse) {
                        toastr.warning("El correo de respuesta es obligatorio.");
                        return;
                    }


                    if (this.programmed === "1" && !this.campaign.step1.date) {
                        toastr.warning("Por favor, selecciona una fecha para el envío programado.");
                        return;
                    }

                    if (!this.excelFile) {
                        toastr.warning("Por favor, carga un archivo Excel antes de continuar.");
                        return;
                    }

                    this.step = 2;
                },
                // Step 2
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
                searchTemplates() {
                    this.currentPage = 1;
                    this.getTemplates();
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
                        'preview' : this.excelPreview[0]
                    }
                    axios.post(`/includes/templates/selectTemplate?id=${templateId}`, data)
                        .then(response => {
                            if (response.data.success) {
                                toastr.success(response.data.message);
                                this.campaign.step2.template_id = templateId;
                                this.campaign.step2.selectedTemplate = response.data.item;
                                this.campaign.step2.data_emails = this.excelPreview
                                this.step = 3;
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
                    this.loading = true;
                    this.campaign.step2.selectedTemplate = null;
                    this.campaign.step2.select_template = null;
                    this.step = 2;
                    this.loading = false;
                },
                // Step 3
                async fetchSelectTemplate() {
                    this.loading = true;
                    if (!this.campaign.step2.select_template) {
                        alert("Por favor seleccione un dato de prueba.");
                        return;
                    }
                    const selectedDataEmail = this.campaign.step2.data_emails.find(
                        data_email => data_email.IDENTIFICADOR === this.campaign.step2.select_template
                    );

                    if (!selectedDataEmail) {
                        alert("El dato seleccionado no es válido.");
                        this.loading = false;
                        return;
                    }

                    const payload = {
                        dataEmail: selectedDataEmail,
                        template: this.campaign.step2.selectedTemplate.json_content
                    };

                    axios.post(`/includes/templates/selectTemplateToShow`,payload)
                        .then((response) => {
                            if (response.data.success) {
                                this.json_content_template = response.data.processed_json;
                                this.renderIframeFromJson(this.json_content_template);
                            } else {
                                toastr.warning(response.data.message || "No se pudo cargar la plantilla.");
                            }
                        })
                        .catch(error => {
                            console.error('Error al cargar datos:', error);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },
                renderIframeFromJson(jsonData) {
                    const container = document.querySelector('#template_content');

                    container.innerHTML = '';

                    const iframe = document.createElement('iframe');
                    iframe.style.width = '100%';
                    iframe.style.height = '600px';
                    iframe.style.border = 'none';

                    const generatedHtml = this.generateHtmlFromJson(jsonData);

                    iframe.onload = () => {
                        const doc = iframe.contentDocument || iframe.contentWindow.document;
                        doc.open();
                        doc.write(generatedHtml);
                        doc.close();
                    };

                    container.appendChild(iframe);
                },
                generateHtmlFromJson(json) {
                    let html = `<div style="font-family: ${json.body.values.fontFamily.label}; color: ${json.body.values.textColor}; background-color: ${json.body.values.backgroundColor}; padding: 10px;">`;

                    json.body.rows.forEach(row => {
                        html += '<div style="display: flex; margin-bottom: 10px;">';

                        row.columns.forEach(column => {
                            html += '<div style="flex: 1; padding: 5px;">';

                            column.contents.forEach(content => {
                                if (content.type === 'heading') {
                                    html += `<${content.values.headingType} style="font-size: ${content.values.fontSize}; text-align: ${content.values.textAlign}; line-height: ${content.values.lineHeight};">${content.values.text}</${content.values.headingType}>`;
                                } else if (content.type === 'text') {
                                    html += `<p style="font-size: ${content.values.fontSize}; text-align: ${content.values.textAlign}; line-height: ${content.values.lineHeight};">${content.values.text}</p>`;
                                } else if (content.type === 'button') {
                                    html += `<a href="${content.values.href.values.href}" target="${content.values.href.values.target}" style="display: inline-block; text-decoration: none; background-color: ${content.values.buttonColors.backgroundColor}; color: ${content.values.buttonColors.color}; padding: ${content.values.padding}; border-radius: ${content.values.borderRadius}; font-size: ${content.values.fontSize}; line-height: ${content.values.lineHeight}; text-align: ${content.values.textAlign};">${content.values.text}</a>`;
                                }
                            });

                            html += '</div>';
                        });

                        html += '</div>';
                    });

                    html += '</div>';
                    return html;
                },
                async submitForm(){
                    if (!confirm('¿Estás seguro de que deseas crear esta campaña?')) {
                        return;
                    }

                    const formData = new FormData();

                    formData.append('name', this.campaign.step1.name);
                    formData.append('subject', this.campaign.step1.subject);
                    formData.append('sender', this.campaign.step1.sender);
                    formData.append('template_id',this.campaign.step2.template_id);
                    formData.append('emailResponse', this.campaign.step1.emailResponse);
                    formData.append('unsubcribe', this.campaign.step1.unsubcribe);
                    formData.append('date', this.campaign.step1.date);
                    formData.append('programmed', this.programmed);
                    formData.append('file', this.excelFile);

                    this.loading = true;

                    try {
                        const response = await axios.post('/includes/campaigns/insertCampaign', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                            }
                        });

                        if (response.data.success) {
                            toastr.success(response.data.message || "Campaña guardada con éxito.");
                            window.location.href="http://sinaptica-crm-dev.test/nuevo-email/campaña"
                        } else {
                            toastr.warning(response.data.message || "No se pudo guardar la campaña.");
                        }
                    } catch (error) {
                        toastr.error("Ocurrió un error al guardar la campaña.");
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                }
            },
        });
    </script>
</body>
</html>