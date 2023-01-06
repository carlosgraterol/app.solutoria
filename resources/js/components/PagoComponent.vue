<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white"><h5>Informacion de pago</h5></div>

                    <div class="card-body">
                        <form class="needs-validation" novalidate id="validation-form" v-for="pago in pagos" :key="pago.id">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label class="form-label" for=""><strong>Usuario</strong></label>
                                    <input type="text" :class="`form-control`" id="" name="" :value="pago.user_id" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-label" for=""><strong>Fecha</strong></label>
                                    <input type="date" :class="`form-control`" id="" name="" :value="pago.fecha" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-label" for=""><strong>Monto</strong></label>
                                    <input :class="`form-control`" id="" name="" :value="pago.monto" />
                                </div>
                                <div class="form-group col-md-3 justify-center-content">
                                    <button type="button" class="btn btn-primary">AGREGAR</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</template>

<script>
    export default {
        props: {
            pagos: { 
                type: Object, 
                default: () => {} 
            },
        },
        data() {
            return {
                form: [...this.pagos],
            }
        },
        mounted() {
            console.log("🚀 ~ pagos", this.pagos);
            console.log(this.pagos.data);
        }
    }
</script>