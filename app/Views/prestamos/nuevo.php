<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Nuevo Finaciamiento
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Nuevo Finaciamiento</h4>
    </div>
    <div class="card-body">
        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?php echo session()->getFlashdata('respuesta')['type']; ?>">
                <?php echo session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
        <form action="<?php echo base_url('prestamos'); ?>" method="post" autocomplete="off">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-group">
                        <label>Buscar cliente</label>
                        <input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo set_value('id_cliente'); ?>">
                        <input type="text" id="cliente" name="cliente" class="form-control" value="<?php echo set_value('cliente'); ?>" placeholder="Nombre">
                        <span class="text-danger" id="errorCliente"></span>
                        <?php if (!empty($errors['id_cliente']) || !empty($errors['cliente'])) { ?>
                            <span class="text-danger"><?php echo $errors['cliente']; ?></span>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label>Buscar vehículo</label>
                        <input type="hidden" id="id_vehiculo" name="id_vehiculo" value="<?php echo set_value('id_vehiculo'); ?>">
                        <input onchange="autoVin()" type="text" id="vehiculo" name="vehiculo" class="form-control" value="<?php echo set_value('vehiculo'); ?>" placeholder="Vehículo">
                        <span class="text-danger" id="errorVehiculo"></span>
                        <?php if (!empty($errors['id_vehiculo']) || !empty($errors['vehiculo'])) { ?>
                            <span class="text-danger"><?php echo $errors['vehiculo']; ?></span>
                        <?php } ?>
                    </div>

                    
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label>Valor del vehículo</label>
                            <input type="hidden" id="id_valor_vehiculo" name="id_valor_vehiculo" value="<?php echo set_value('id_valor_vehiculo'); ?>">
                            <input onchange="substractValue()" type="text" id="valor_vehiculo" name="valor_vehiculo" class="form-control" value="<?php echo set_value('valor_vehiculo'); ?>" placeholder="Valor Vehículo">
                            <span class="text-danger" id="errorValorVehiculo"></span>
                            <?php if (!empty($errors['id_valor_vehiculo']) || !empty($errors['vehiculo'])) { ?>
                                <span class="text-danger"><?php echo $errors['vehiculo']; ?></span>
                            <?php } ?>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Importe a Financiar</label>
                            <input type="text" onkeyup="calcularEnganche()" step="any" min="0" id="importe_credito" name="importe_credito" class="form-control" value="<?php echo set_value('importe_credito'); ?>" placeholder="Importe">
                            <?php if (!empty($errors['importe_credito'])) { ?>
                                <span class="text-danger"><?php echo $errors['importe_credito']; ?></span>
                            <?php } ?>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Modalidad</label>
                            <select class="form-select" name="modalidad">
                                <!-- <option value="">Seleccionar</option> -->
                                <!-- <option value="DIARIO" <?= set_select('modalidad', 'DIARIO', (!empty($modalidad) && $modalidad == 'DIARIO' ? true : false)) ?>>DIARIO</option>
                                <option value="SEMANAL" <?= set_select('modalidad', 'SEMANAL', (!empty($modalidad) && $modalidad == 'SEMANAL' ? true : false)) ?>>SEMANAL</option> -->
                                <option value="MENSUAL" <?= set_select('modalidad', 'MENSUAL', (!empty($modalidad) && $modalidad == 'MENSUAL' ? true : false)) ?>>MENSUAL</option>
                                <!-- <option value="ANUAL" <?= set_select('modalidad', 'ANUAL', (!empty($modalidad) && $modalidad == 'ANUAL' ? true : false)) ?>>ANUAL</option> -->
                            </select>
                            <?php if (!empty($errors['modalidad'])) { ?>
                                <span class="text-danger"><?php echo $errors['modalidad']; ?></span>
                            <?php } ?>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label>Tasa Interés Mensual</label>
                            <input type="number" step="any" min="0" id="tasa_mensual" name="tasa_mensual" class="form-control" value="1.6" placeholder="10">       
                        </div>

                        <div class="col-lg-6 form-group">
                            <label>Tasa Interés Total</label>
                            <input type="number" step="any" min="1" id="tasa_interes" name="tasa_interes" class="form-control" value="<?php echo set_value('tasa_interes', $empresa['tasa_interes']); ?>" placeholder="10" readonly>
                            <?php if (!empty($errors['tasa_interes'])) { ?>
                                <span class="text-danger"><?php echo $errors['tasa_interes']; ?></span>
                            <?php } ?>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Cuotas</label>
                            <select class="form-select" id="cuotas" name="cuotas" onchange="calcularInteresTotal()">
                                <option value="">Seleccionar</option>
                                <?php for ($i = 1; $i <= $empresa['cuotas']; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?= set_select('cuotas', $i, (!empty($cuotas) && $cuotas == $i ? true : false)) ?>>
                                        Cuota: <?php echo $i; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <?php if (!empty($errors['cuotas'])) { ?>
                                <span class="text-danger"><?php echo $errors['cuotas']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Generar</button>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>Enganche</label>
                        <input type="text" step="any" min="0" id="enganche" name="enganche" class="form-control" value="<?php echo set_value('enganche'); ?>" placeholder="0.00" readonly>
                    </div>
                    <div class="form-group">
                        <label>Importe x cuota</label>
                        <input type="text" id="importe_cuota" name="importe_cuota" class="form-control" value="<?php echo set_value('importe_cuota'); ?>" placeholder="0.00" readonly>
                    </div>
                    <div class="form-group">
                        <label>Total a pagar</label>
                        <input type="text" id="total_pagar" name="total_pagar" class="form-control" value="<?php echo set_value('total_pagar'); ?>" placeholder="0.00" readonly>
                    </div>
                    <div class="form-group">
                        <label>Interes generado</label>
                        <input type="text" id="interes_generado" name="interes_generado" class="form-control" value="<?php echo set_value('interes_generado'); ?>" placeholder="0.00" readonly>
                    </div>
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" class="form-control" value="<?php echo set_value('fecha'); ?>" >
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
<?= $this->endSection('content'); ?>

<?= $this->section('js'); ?>
<script src="<?php echo base_url('assets/js/pages/prestamos.js'); ?>"></script>
<?= $this->endSection('js'); ?>