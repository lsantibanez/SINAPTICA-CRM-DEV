<?PHP
error_reporting(E_ALL & ~E_NOTICE);

require_once('../class/db/DB.php');
$db = new DB();
require_once('../class/session/session.php');
include("../class/global/global.php");
$objetoSession = new Session('1,2,3,4,5,6', false);
$objetoSession->crearVariableSession($array = array("idMenu" => "sms,campaña"));
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
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css"/>
    <link rel="stylesheet" href="/css/extra/flatpickr.min.css">
    <link rel="stylesheet" href="/css/extra/toastr.min.css">
    <link href="/css/global/global.css" rel="stylesheet">
    <link href="/css/global/estilos.css" rel="stylesheet">
    <style>
        small {
            display: block;
            margin-top: 5px;
            font-size: 0.9em;
            color: #555;
        }
        .bg-gray{
            background-color: #ececf9;

        }
        .shadow-lg{
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
            0 4px 6px -2px rgba(0, 0, 0, 0.05);
            padding: 20px;
            border-radius: 35px;

        }
    </style>
</head>
<body>
<div id="container" class="effect mainnav-lg">
    <?php include '../layout/header.php'; ?>
    <div class="boxed">
        <div id="content-container">
            <div id="page-title">
                <h1 class="page-header text-overflow">Sms</h1>
            </div><!-- page title -->
            <ol class="breadcrumb">
                <li><a href="#">Sms</a></li>
                <li class="active">Mensajería</li>
            </ol><!-- breadcrumb -->
            <div id="page-content">
                <div id="appConsultaTemplates">
                    <!--Tabla -->
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <h4>Crear Sms</h4>
                                    <form @submit.prevent="submitForm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label for="name" class="form-label">Nombre de la Campaña</label>
                                                    <input type="text" id="name" class="form-control"
                                                           v-model="item.name" required/>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label for="file" class="form-label">Seleccione archivo de
                                                        carga</label>
                                                    <input type="file" class="form-control" @change="handleFileUpload"
                                                           id="file" accept=".xlsx,.xls"/>
                                                </div>
                                            </div>

                                            <div class="col-md-12 p-0" v-if="isUploaded">
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label for="phone" class="form-label">Seleccione columna
                                                            teléfono del archivo</label>
                                                        <select class="form-control" v-model="item.phone" id="phone">
                                                            <option value="" disabled selected>Seleccione una opción
                                                            </option>
                                                            <option :selected="phone === item.phone"
                                                                    v-for="phone in phones" :key="phone" :value="phone">
                                                                {{ phone }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label for="identity" class="form-label">Seleccione Documento o
                                                            DNI. Opcional *</label>
                                                        <select class="form-control" v-model="item.identity"
                                                                id="identity">
                                                            <option value="" disabled selected>Seleccione una opción
                                                            </option>
                                                            <option :selected="identity === item.identity"
                                                                    v-for="identity in identities" :key="identity"
                                                                    :value="identity">
                                                                {{ identity }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!--                                                <div class="col-md-6">-->
                                                <!--                                                    <div class="mb-4">-->
                                                <!--                                                        <label for="select_wallet" class="form-label">Seleccione cartera-->
                                                <!--                                                            a-->
                                                <!--                                                            Asignar (Opcional)</label>-->
                                                <!--                                                        <select class="form-control" v-model="select_wallet"-->
                                                <!--                                                                id="select_wallet">-->
                                                <!--                                                            <option value="" disabled selected>Seleccione una opción-->
                                                <!--                                                            </option>-->
                                                <!--                                                        </select>-->
                                                <!--                                                    </div>-->
                                                <!--                                                </div>-->
                                                <!---->
                                                <!--                                                <div class="col-md-6">-->
                                                <!--                                                    <div class="mb-4">-->
                                                <!--                                                        <label for="wallet" class="form-label">Crear nueva-->
                                                <!--                                                            Cartera</label>-->
                                                <!--                                                        <div class="d-flex gap-10">-->
                                                <!--                                                            <input type="text" class="form-control" v-model="wallet"-->
                                                <!--                                                                   id="wallet"/>-->
                                                <!--                                                            <button type="button" class="btn btn-primary">Guardar-->
                                                <!--                                                                Cartera-->
                                                <!--                                                            </button>-->
                                                <!--                                                        </div>-->
                                                <!--                                                    </div>-->
                                                <!--                                                </div>-->

                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-4">
                                                    <label for="data_sms" class="form-label">Variables Detectadas del
                                                        archivo <small>(Hacer click para seleccionar)</small> </label>
                                                    <div class="d-flex gap-10">
                                                        <div class="mb-3" v-for="(item, index) in customVariables"
                                                             :key="index">
                                                            <button class="badge bg-primary p-3 text-md border-0 b-radius-1"
                                                                    @click.prevent="insertVariable(item)">{{ item }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-4">
                                                    <label for="message" class="form-label">Escriba el mensaje a
                                                        enviar</label>
                                                    <textarea
                                                            v-model="item.message"
                                                            id="message"
                                                            rows="10"
                                                            class="form-control"
                                                            @input="updateCharacterCount"></textarea>

                                                    <small>{{ characterCount }} caracteres</small>
                                                </div>
                                            </div>
                                            <div v-if="isPreview" class="col-md-12 bg-gray shadow-lg my-20">

                                                <span class="form-label text-md"><strong>Preview</strong></span>
                                                <div v-if="!topRecords.length">
                                                    <p>No hay datos para mostrar en la vista previa.</p>
                                                </div>
                                                <table v-else class="table">
                                                    <thead>
                                                    <tr>
                                                        <th v-for="(header, index) in tableHeaders" :key="'header-' + index">
                                                            {{ header }}
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="(row, rowIndex) in topRecords" :key="'row-' + rowIndex">
                                                        <td v-for="(header, colIndex) in tableHeaders" :key="'col-' + colIndex">
                                                            {{ row[header] }}
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="row bg-gray shadow-lg mb-20">
                                            <h4>Información importante sobre las cantidades de SMS a enviar</h4>
                                            <p>
                                                Si el mensaje <strong>no posee</strong> caracteres especiales o acentos.
                                            </p>
                                            <ul>
                                                <li>
                                                    Si el largo sobrepasa los <strong>160 caracteres</strong> se consideran los primeros <strong>157</strong> un SMS
                                                    y <strong>por cada 157 caracteres adicionales o su diferencia será 1 SMS</strong>.
                                                    <ul>
                                                        <li>Un texto de 175 caracteres se enviarán 2 SMS a cada teléfono de la lista, 1 SMS por los primeros 157 y un segundo de 18 caracteres restantes.</li>
                                                        <li>Un texto de 471 caracteres se enviarán 3 SMS a cada teléfono de la lista, 2 de 157 y un tercero por los 155 caracteres restantes.</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                            <p>
                                                Por lo que si el texto del mensaje es de 175 caracteres y son 10 teléfonos en la lista se enviarán 20 SMS,
                                                o si el texto del mensaje es de 471 y 10 teléfonos en la lista se enviarán 30 SMS.
                                            </p>
                                            <p>
                                                Si el mensaje <strong>posee</strong> caracteres especiales o acentos.
                                            </p>
                                            <ul>
                                                <li>
                                                    Si el largo sobrepasa los <strong>70 caracteres</strong> se consideran los primeros <strong>67</strong> un SMS
                                                    y <strong>por cada 67 caracteres adicionales o su diferencia será 1 SMS</strong>.
                                                    <ul>
                                                        <li>Un texto de 120 caracteres se enviarán 2 SMS a cada teléfono de la lista, 1 SMS por los primeros 67 y un segundo SMS de 53 caracteres.</li>
                                                        <li>Un texto de 168 caracteres se enviarán 3 SMS a cada teléfono de la lista, 2 SMS de 67 y un tercero SMS por los 34 caracteres restantes.</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                            <p>
                                                Por lo que si el texto del mensaje es de 120 caracteres y son 10 teléfonos en la lista se enviarán 20 SMS,
                                                o si el texto del mensaje es de 168 y 10 teléfonos en la lista se enviarán 30 SMS.
                                            </p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center my-4 gap-10">

                                            <button :disabled="loading" class="btn btn-primary">
                                                <span v-if="!loading">Guardar Campaña</span>
                                                <span v-else>Cargando...</span>
                                            </button>
                                            <span v-if="loading" class="spinner-border spinner-border-sm text-primary"
                                                  role="status"></span>
                                            <button @click.prevent="clearMessage" class="btn btn-warning">Limpiar</button>
                                        </div>
                                    </form>

                                    <!-- Modal -->
                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                                         aria-labelledby="myModalLabel">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title" id="myModalLabel">Visualización de
                                                        mensajes</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Cant. SMS</th>
                                                            <th>Contiene acentos</th>
                                                            <th>Largo</th>
                                                            <th>Mensaje</th>
                                                            <th>Teléfono</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="(data, index) in previewMessage" :key="index">
                                                            <td>{{ data.cantSms }}</td>
                                                            <td>{{ data.contieneAcentos }}</td>
                                                            <td>{{ data.largo }}</td>
                                                            <td>{{ data.mensaje }}</td>
                                                            <td>{{ data.telefono }}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                                        Cerrar
                                                    </button>
                                                    <button type="button" class="btn btn-primary">Guardar cambios
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
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
    <script>
        var app = new Vue({
            el: '#appConsultaTemplates',
            data: {
                programmed: 0,
                item: {
                    id: null,
                    name: '',
                    phone: 0,
                    identity: '',
                    message: ''
                },
                characters: 0,
                characterCount: 0,
                isUploaded: false,
                customVariables: [],
                select_wallet: '',
                phones: [],
                identities: [],
                wallet: '',
                topRecords: [],
                excelFile: null,
                excelValidated: false,
                excelPreview: [],
                tableHeaders: [],
                tableData: [],
                previewMessage: [],
                isPreview: false,
                loading: false,
            },
            mounted() {
            },
            methods: {

                clearMessage() {
                    const confirmed = window.confirm("¿Estás seguro de que deseas limpiar el mensaje?");

                    if (confirmed) {
                        this.item.message = '';
                    }
                },
                insertVariable(variable) {
                    this.item.message += `[${variable}]`;
                    this.updateCharacterCount();
                },
                updateCharacterCount() {
                    this.characterCount = this.item.message.length;
                },
                async handleFileUpload(event) {
                    this.loading = true;
                    this.excelFile = event.target.files[0];

                    if (!this.excelFile) {
                        toastr.warning("No se seleccionó ningún archivo.");
                        this.loading = false;
                        return;
                    }

                    const formData = new FormData();
                    formData.append("file", this.excelFile);

                    try {
                        const response = await axios.post('/includes/sms/verifyExcel', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        });

                        if (response.data.success) {
                            if (this.topRecords) {
                                this.isPreview = false;
                                this.customVariables = [];
                                this.tableHeaders = [];
                                this.topRecords = [];
                            }

                            this.customVariables = response.data.headers;
                            this.phones = response.data.headers;
                            this.identities = response.data.headers;
                            this.topRecords = response.data.topRecords || [];
                            this.isPreview = true;
                            this.isUploaded = true;
                            toastr.success("Archivo procesado correctamente.");
                        } else {
                            toastr.warning(response.data.message);
                        }
                    } catch (error) {
                        console.error("Error al enviar el archivo:", error);
                        toastr.error("Error al procesar el archivo.");
                    } finally {
                        this.loading = false;
                    }
                },
                setTableHeaders() {
                    if (this.topRecords.length > 0) {
                        this.tableHeaders = Object.keys(this.topRecords[0]);
                    } else {
                        this.tableHeaders = [];
                    }
                },
                async submitForm() {
                    this.loading = true;
                    if (!this.item.name || !this.item.phone || !this.item.message) {
                        toastr.warning("Por favor, complete todos los campos requeridos.");
                        return;
                    }

                    $('#myModal').modal('show');

                    const formatMessage = this.item.message.trim().replace(new RegExp(String.fromCharCode(160), "g"), " ");

                    const previewData = this.topRecords.map(record => {
                        let message = formatMessage;

                        this.customVariables.forEach(variable => {
                            const regex = new RegExp(`\\[${variable}\\]`, 'g');
                            if (record[variable]) {
                                message = message.replace(regex, record[variable]);
                            } else {
                                message = message.replace(regex, '');
                            }
                        });

                        const largoMensaje = message.length;

                        let partes = 1;
                        let withCharacter = 'NO';

                        // Detectar caracteres especiales o acentos
                        const regexEspeciales = /[^\u0000-\u007F]/;
                        if (regexEspeciales.test(message)) {
                            withCharacter = 'SI';

                            if (largoMensaje > 70) {
                                partes = Math.ceil((largoMensaje - 67) / 67) + 1;
                            }
                        } else {

                            if (largoMensaje > 160) {
                                partes = Math.ceil((largoMensaje - 157) / 157) + 1;
                            }
                        }

                        return {
                            cantSms: partes,
                            contieneAcentos: withCharacter,
                            largo: largoMensaje,
                            mensaje: message,
                            telefono:  record[this.item.phone],
                        };
                    });

                    this.previewMessage = previewData;

                    const confirmed = new Promise((resolve, reject) => {
                        $('#myModal').on('hidden.bs.modal', () => {
                            resolve(false);
                        });
                        $('#myModal').on('click', '.btn-primary', () => {
                            resolve(true);
                        });
                    });

                    const userConfirmed = await confirmed;

                    if (!userConfirmed) {
                        this.loading = false;
                        return;
                    }

                    if (!confirm('¿Estás seguro de que deseas crear esta campaña?')) {
                        return;
                    }

                    try {
                        const formData = new FormData();
                        formData.append("name", this.item.name);
                        formData.append("phone", this.item.phone ?? null);
                        formData.append("identity", this.item.identity);
                        formData.append("message", this.item.message);

                        if (this.excelFile) {
                            formData.append("file", this.excelFile);
                        }

                        const response = await axios.post("/includes/sms/insertSms", formData, {
                            headers: {"Content-Type": "multipart/form-data"},
                        });

                        if (response.data.success) {
                            toastr.success(response.data.message || "Campaña guardada con éxito.");
                            window.location.href = "http://sinaptica-crm-dev.test/sms/listar-sms"
                        } else {
                            toastr.warning(response.data.message || "No se pudo guardar la campaña.");
                        }

                    } catch (error) {
                        console.error("Error al enviar el formulario:", error);
                        if (error.response) {
                            toastr.error(error.response.data.message || "Ocurrió un error al procesar la solicitud.");
                        } else {
                            toastr.error("Error de conexión con el servidor.");
                        }
                    } finally {
                        this.loading = false;
                    }
                }
            },
            watch: {
                topRecords: {
                    handler(newValue) {
                        this.setTableHeaders();
                    },
                    immediate: true,
                },
            },
        });
    </script>
</body>
</html>