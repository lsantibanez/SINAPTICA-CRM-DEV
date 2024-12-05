<?PHP
error_reporting(E_ALL & ~E_NOTICE);

require_once('../class/db/DB.php');
$db = new DB();
require_once('../class/session/session.php');
include("../class/global/global.php");
$objetoSession = new Session('1,2,3,4,5,6',false);
$objetoSession->crearVariableSession($array = array("idMenu" => "constas,constasrut"));
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
        #dropzone-container .dz-success-mark,
        #dropzone-container .dz-error-mark {
            display: none;
        }

        #dropzone-container .dz-remove {
            color: red !important;
            font-size: 18px;
            cursor: pointer;
        }

        #dropzone-container .dz-remove::after {
            content: "✖";
        }

        #dropzone-container  .dz-success-custom {
            display: flex;
            justify-content: center;
            align-items: center;
            color: green;
            font-size: 20px;
            font-weight: bold;
            margin-top: 5px;
        }

        #dropzone-container .dz-success-custom::before {
            content: "✔ Archivo válido";
            margin-right: 10px;
        }
        #dropzone-container .dz-error-custom {
            display: flex;
            justify-content: center;
            align-items: center;
            color: red;
            font-size: 20px;
            font-weight: bold;
            margin-top: 5px;
        }

        #dropzone-container .dz-error-custom::before {
            content: "❌ Archivo no válido";
            margin-right: 10px;
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
                <li class="active">Actualizar Campaña</li>
            </ol><!-- breadcrumb -->
            <div id="page-content">
                <div id="appConsultaTemplates">
                    <!--Tabla -->
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <h4>Actualizar Campaña</h4>
                                    <form @submit.prevent="submitForm">
                                        <div class="row">

                                            <div class="col-md-8">
                                                <div class="mb-4">
                                                    <label for="name" class="form-label">Nombre de la Campaña</label>
                                                    <input type="text" id="name" class="form-control" v-model="campaign.name" required/>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-4">
                                                    <label for="date" class="form-label">Envío Programado</label>
                                                    <select class="form-control mb-0" v-model="campaign.date" id="date">
                                                        <option selected value="0">No</option>
                                                        <option value="1">Si</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-4">
                                                    <label for="subject" class="form-label">Asunto:</label>
                                                    <input type="text" id="subject" class="form-control" v-model="campaign.subject" required/>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label for="sender" class="form-label">Nombre remitente:</label>
                                                    <input type="text" id="sender" class="form-control" v-model="campaign.sender" required/>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label for="emailResponse" class="form-label">Correo de respuesta:</label>
                                                    <input type="email" id="emailResponse" class="form-control" v-model="campaign.emailResponse"
                                                           required/>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-4">
                                                    <label for="unsubcribe" class="form-label">Url desuscribir:</label>
                                                    <input type="url" id="unsubcribe" class="form-control" v-model="campaign.unsubcribe"
                                                           required/>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="d-flex justify-content-between align-items-center my-4">
                                            <button class="btn btn-primary">
                                                Guardar cambios
                                            </button>
                                            <span v-if="loading" class="spinner-border spinner-border-sm text-primary" role="status"></span>
                                            <a class="btn btn-warning" href="/nuevo-email/campaña">Regresar</a>
                                        </div>
                                    </form>
                                    <div class="col-md-12">
                                        <div class="mb-4" id="dropzone-container">
                                            <label for="file" class="form-label">Carga de archivos base (Excel):</label>
                                            <form action="/target" class="dropzone" id="my-dropzone"></form>
                                        </div>
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
    <script src="https://editor.unlayer.com/embed.js"></script>
    <script src="/js/extra/html2canvas.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        var app = new Vue({
            el: '#appConsultaTemplates',
            data: {
                id: '',
                campaign: {
                    name: '',
                    date: 0,
                    subject: '',
                    sender: '',
                    emailResponse: '',
                    unsubcribe: '',
                },
                excelFile: null,
                excelValidated: false,
                excelPreview: [],
                loading: false,
            },
            mounted() {
                const self = this;
                if (Dropzone.instances.length > 0) {
                    Dropzone.instances.forEach(instance => instance.destroy());
                }

                this.dropzone = new Dropzone("#my-dropzone", {
                    url: "/api/dummy-endpoint",
                    autoProcessQueue: false,
                    paramName: "file",
                    acceptedFiles: ".xls,.xlsx",
                    dictDefaultMessage: "Arrastra un archivo Excel aquí para cargarlo",
                    addRemoveLinks: true,
                    dictRemoveFile: "",
                    init: function () {
                        this.on("addedfile", function (file) {
                            self.excelFile = file;
                            self.validateExcel(file, this);
                        });

                        this.on("removedfile", function () {
                            self.excelFile = null;
                            self.excelValidated = false;
                            self.excelPreview = [];
                        });
                    },
                });
                const params = new URLSearchParams(window.location.search);
                this.id = params.get('id');
                this.fetchCampaignData(this.id)
            },
            methods: {
                async fetchCampaignData(campaignId){
                    this.loading = true;
                    axios.get(`/includes/campaigns/getCampaign?id=${campaignId}`)
                        .then(response => {
                            this.campaign = response.data.item;
                            this.campaign.date = 0;
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
                async validateExcel(file, dropzone) {
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

                            const filePreviewElement = file.previewElement;
                            const successIcon = document.createElement("div");
                            successIcon.classList.add("dz-success-custom");
                            filePreviewElement.appendChild(successIcon);

                            toastr.success(response.data.message);
                        } else {
                            this.handleInvalidFile(file, dropzone, response.data.message);
                        }
                    } catch (error) {
                        this.handleInvalidFile(file, dropzone, 'Ocurrió un error al procesar la solicitud.');
                    } finally {
                        this.loading = false;
                    }
                },
                handleInvalidFile(file, dropzone, message) {
                    this.excelValidated = false;
                    this.excelPreview = [];

                    const filePreviewElement = file.previewElement;
                    const errorIcon = document.createElement("div");
                    errorIcon.classList.add("dz-error-custom");
                    filePreviewElement.appendChild(errorIcon);

                    dropzone.removeFile(file);

                    toastr.warning(message || "Archivo no válido");
                },
                async submitForm() {
                    this.loading = true;

                    try {
                        const formData = new FormData();
                        formData.append("id", this.id);
                        formData.append("name", this.campaign.name);
                        formData.append("date", this.campaign.date);
                        formData.append("subject", this.campaign.subject);
                        formData.append("sender", this.campaign.sender);
                        formData.append("emailResponse", this.campaign.emailResponse);
                        formData.append("unsubcribe", this.campaign.unsubcribe);

                        if (this.excelFile) {
                            formData.append('file', this.excelFile);
                        }

                        const response = await axios.post("/includes/campaigns/updateCampaign", formData, {
                            headers: { "Content-Type": "multipart/form-data" },
                        });

                        if (response.data.success) {
                            toastr.success(response.data.message);
                            window.location.href = "http://sinaptica-crm-dev.test/nuevo-email/seleccionar-plantilla?id="+this.id;
                        } else {
                            toastr.error(response.data.message);
                        }
                    } catch (error) {
                        if (error.response) {
                            toastr.error(error.response.data.message || 'Ocurrió un error al procesar la solicitud.');
                        } else {
                            toastr.error('Error de conexión con el servidor.');
                        }
                    } finally {
                        this.loading = false;
                    }
                },
            },
        });
    </script>
</body>
</html>