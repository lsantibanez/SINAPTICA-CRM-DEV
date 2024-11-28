<div id="RutSearcher" class="demo-set">
    <div class="demo-set-body bg-blanco">
        <div id="RutSearcher-alert"></div>
        <div class="nano" style="height:520px">
            <div class="nano-content">
                <div class="row">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">Buscador de Rut</div>
                        </div>
                        <div class="panel-body">
                            <div class="col-sm-6 col-sm-offset-3">
                                <div class="form-group">
                                    <label class="control-label">Ingrese el Rut:</label>
                                    <input type="text" id="RutSearcherRutText" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-4 col-sm-offset-4" style="text-align: center;">
                                <button id="RutSearcherFind" class="btn btn-primary" style="width: 100%">Buscar</button>
                            </div>
                        </div>
                        <div class="panel" id="RutSearcherInfoContainer">
                            <div class="panel-heading bg-primary">
                                <div class="panel-control ">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#TabDPersona" data-toggle="tab">Datos Personales</a></li>
                                        <li><a href="#TabDDeuda" data-toggle="tab">Deudas</a></li>
                                        <li><a href="#TabDfono_cob" data-toggle="tab">Teléfonos</a></li>
                                        <li><a href="#TabDDirecciones" data-toggle="tab">Direcciones</a></li>
                                        <li><a href="#TabDMail" data-toggle="tab">Correos</a></li>
                                        <li><a href="#TabDGestion" data-toggle="tab">Gestiones</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="TabDPersona">
                                        <table id="TableTabDPersona" cellspacing="0" width="100%">
                                            <thead>
                                                <tr></tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade in" id="TabDDeuda">
                                        <table id="TableTabDDeuda" cellspacing="0" width="100%">
                                            <thead>
                                                <tr></tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade in" id="TabDfono_cob">
                                        <table id="TableTabDfono_cob" cellspacing="0" width="100%">
                                            <thead>
                                                <tr></tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="TabDDirecciones">
                                        <table id="TableTabDDirecciones" cellspacing="0" width="100%">
                                            <thead>
                                                <tr></tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="TabDMail">
                                        <table id="TableTabDMail" cellspacing="0" width="100%">
                                            <thead>
                                                <tr></tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="TabDGestion">
                                        <table id="TableTabDGestion" cellspacing="0" width="100%">
                                            <thead>
                                                <tr></tr>
                                            </thead>
                                            <tbody></tbody>
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
    <button id="RutSearcher-btn" style="position: absolute; left: 50%;" class="btn btn-sm btn-primary"><i class="fa fa-search "></i></button>
</div>
<link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
<script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>