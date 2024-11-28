<?php
require_once '../../class/session/session.php';
include '../../class/global/global.php';
require_once('../../class/db/DB.php');

$db = new DB();
$objetoSession = new Session('1,2,3,4,5,6',false);
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")) {
  $objetoSession->borrarVariablesSession();
  $objetoSession->logoutGoTo("../../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = $_SESSION['cedente'];
$nombreUsuario = $_SESSION['nombreUsuario'];
$idUsuarioLogin = $_SESSION['id_usuario'];


?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRM Sinaptica | Software de Estrategia</title>
  <link href="/css/bootstrap.min.css" rel="stylesheet">
  <link href="/css/nifty.min.css" rel="stylesheet">
  <link href="/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="/css/global/global.css" rel="stylesheet">
</head>
<body>
  <div id="container" class="effect mainnav-lg">
    <?php include("../../layout/header.php"); ?>
    <div class="boxed">
      <div id="content-container">
        <div id="page-title">
          <h1 class="page-header text-overflow">Segmentación</h1>
        </div>
        <ol class="breadcrumb">
          <li><a href="#">Descargos</a></li>
          <li class="active">Gestionar</li>
        </ol>
        <div id="page-content">
          <div id="app-descargos">
            <div class="row">
              <div class="col-lg-12">
                <div class="panel">
                  <div class="panel-body">
                    <div class="row" style="padding: 12px;" v-if="message.text !== ''">
                      <div class="col-md-12">
                        <div class="alert" :class="'alert-' + message.type" role="alert" style="border-radius: 5px;">
                          {{ message.text }}
                        </div>
                      </div>
                    </div>
                    <div v-if="filename === ''">
                      <div class="row" style="padding: 0 12px;">
                        <div class="col-lg-12">
                          <h4 style="margin-top: 0;">Seleccionar archivo de saldos</h4>
                          <div class="progress" role="progressbar" aria-label="Example 20px high" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100" style="height: 20px" v-if="progress > 0">
                            <div class="progress-bar" :style="'width: '+ progress +'%'">{{ progress }} %</div>
                          </div>
                        </div>
                      </div>
                      <div class="row" style="padding: 0 12px;">
                        <div class="col-lg-12">
                          <div class="input-group">
                            <input type="file" class="form-control" id="file" ref="file" @change="selectFile" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                            <span class="input-group-btn">
                              <button class="btn btn-primary" type="button" @click="uploadFile" :disabled="file === ''">Subir archivo</button>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div v-else>
                      <div class="row" style="padding: 0 12px;">
                        <div class="col-lg-12">
                          <table class="table table-sm">
                            <thead>
                              <tr>
                                <th style="width: 20%;">Id</th>
                                <th style="width: 15%;">Archivo</th>
                                <th style="width: 20%;">Configuración</th>
                                <th>Relación</th>
                                <th style="text-align: center; width: 15%;">Acciones</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>{{ info_read.code }}</td>
                                <td>{{ info_read.file }}</td>
                                <td>{{ info_read.configuration }}</td>
                                <td>
                                  <ul style="padding-left: 5px;">
                                    <li v-for="(item, index) in info_read.relation" :key="index">
                                      [{{ item.name }}] => {{ item.file }}  
                                    </li>
                                  </ul>
                                </td>
                                <td>
                                  <button type="button" class="btn btn-block btn-primary mb-3" @click.prevent="readFile">Procesar</button><br/>
                                  <button type="button" class="btn btn-block btn-danger"  @click.prevent="dropFile">Descartar</button>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php include("../../layout/main-menu.php"); ?>
      </div>
    </div>
  </div>
  <script src="/js/jquery-2.2.1.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/nifty.min.js"></script>
  <script src="/js/extra/vue.min.js"></script>
  <script src="/js/extra/axios.min.js"></script>
  <script>
    var app = new Vue({
      el: '#app-descargos',
      data: {
        message: 'Hello Vue!',
        file: '',
        progress: 0,
        filename: '',
        info_read: null,
        message: {
          type: 'info',
          text: ''
        },
      },
      methods: {
        selectFile() {
          this.file = this.$refs.file.files[0];
        },
        uploadFile(e) {
          const boton = e.target;
          let formData = new FormData();
          formData.append('file', this.file);

          boton.disabled = true;

          const config = {
            headers: { 'Content-Type': 'multipart/form-data' },
            onUploadProgress: progressEvent => {
              let percent = (progressEvent.loaded / progressEvent.total) * 100;
              // console.log(`${percent} %`);
              this.progress = percent;
            }
          }

          axios.post('/includes/estrategia/descargos/upload_file.php', formData, config).then(response => {
            console.log(response.data)
            const data = response.data
            if (data.success) {
              this.message.text = data.message
              this.message.type = 'success'
              this.filename = data.info.file
              this.info_read = data.info              
            } else {
              this.message.text = data.message
              this.message.type = 'danger'
              boton.disabled = false;
            }
            setTimeout(() => {
              this.progress = 0;
              boton.disabled = false;
            }, 2500)

            setTimeout(() => {
              this.message.text = ''
              this.message.type = 'info'
            }, 4000)
          }).catch(error => {
            console.error(error)
            this.message.text = 'ERROR de ejecución'
            this.message.type = 'danger'
            boton.disabled = false;
            setTimeout(() => {
              this.message.text = ''
              this.message.type = 'info'
            }, 4000)
          })
        },
        readFile(e) {
          const boton = e.target
          if (this.filename !== '') {
            boton.disabled = true
            const formData = {
              uuid: this.info_read.code,
            }
            axios.post('/includes/estrategia/descargos/read_file.php', formData)
            .then(response => {
              console.log(response.data);
              const data = response.data;
              if (data.success) {
                this.message.text = data.message
                this.message.type = 'success'
                this.info_read = data.info;
              } else {
                this.message.text = data.message
                this.message.type = 'warning'
              }
              boton.disabled = false;
              setTimeout(() => {
                this.message.text = ''
                this.message.type = 'info'
              }, 4000)
            })
            .catch(error => {
              console.error(error);
              this.message.text = 'ERROR'
              this.message.type = 'danger'
              boton.disabled = false;
              setTimeout(() => {
                this.message.text = ''
                this.message.type = 'info'
              }, 4000)
            })
          }
        },
        dropFile(e) {
          const boton = e.target
        }
      },
    });
  </script>
</body>
</html>