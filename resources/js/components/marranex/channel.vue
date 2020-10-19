<template>
    <div>
        <div class="card">
            <div class="card-header text-white franja">Catálogo de Canales</div>
            <div class="card-body">
                <el-form ref="form" :model="form" :rules="rules">
                    <el-row :gutter="10" class="mt-2">
                        <el-col :xs="25" :sm="6" :md="8" :lg="12" :xl="12">
                            <el-form-item prop="name">
                                <el-input v-model="form.name">
                                    <template slot="prepend">Nombre:</template>
                                </el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :xs="25" :sm="6" :md="8" :lg="12" :xl="12">
                            <el-form-item prop="weights">
                                <el-input v-model="form.weights">
                                    <template slot="prepend">Peso:</template>
                                </el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row :gutter="10" class="mt-2">
                        <el-col :xs="25" :sm="6" :md="8" :lg="12" :xl="12">
                            <el-form-item prop="cost">
                                <el-input v-model="form.cost">
                                    <template slot="prepend">Costo:</template>
                                </el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :xs="25" :sm="6" :md="8" :lg="12" :xl="12">
                            <el-form-item prop="decrease">
                                <el-input v-model="form.decrease">
                                    <template slot="prepend">Merma:</template>
                                </el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-form-item>
                        <el-button
                            type="danger"
                            @click="onSubmit('form')"
                            v-loading.fullscreen.lock="handler.handlerLoading.fullscreenLoading">
                            Guardar
                        </el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
        <el-card class="mt-3">
            <el-table border :empty-text="handler.handlerTables.emptys" :data="handler.handlerList.listChannel.slice((handler.handlerTables.currentPage - 1) * handler.handlerTables.pagesize, handler.handlerTables.currentPage * handler.handlerTables.pagesize)">
                <el-table-column label="Nombre" prop="name" header-align="center"></el-table-column>
                <el-table-column label="Peso" prop="weights" header-align="center"></el-table-column>
                <el-table-column label="Costo" prop="cost" header-align="center"></el-table-column>
                <el-table-column label="Merma" prop="decrease" header-align="center"></el-table-column>
            </el-table>
            <div style="text-align: left; margin-top: 30px">
                <el-pagination
                    background
                    layout="total,prev, pager, next"
                    :total="handler.handlerTables.total"
                    @current-change="current_change">
                </el-pagination>
            </div>
        </el-card>

    </div>
</template>

<script>
export default {
    name: "channel.vue",
    data() {
        return {
            handler: {
                handlerTables:
                    {
                        emptys: "Sin Registros",
                        total: 0,
                        currentPage: 1,
                        pagesize: 10,
                    },
                handlerLoading:
                    {
                        fullscreenLoading: false
                    },
                handlerURL:
                    {
                        addChannel: "channel",
                        getChannel: "channel",
                    },
                handlerList:
                    {
                        listChannel: []
                    }
            },
            form: {
              name: "",
              weights: "",
              cost: "",
              decrease: ""
            },
            rules: {
                name: [
                    {
                        required: true,
                        message: "Ingrese el Nombre",
                        trigger: "blur",
                    }
                ],
                weights: [
                    {
                        required: true,
                        message: "Ingrese el Peso",
                        trigger: "blur",
                    }
                ],
                cost: [
                    {
                        required: true,
                        message: "Ingrese el Costo",
                        trigger: "blur",
                    }
                ],
                decrease: [
                    {
                        required: true,
                        message: "Ingrese la Merma",
                        trigger: "blur",
                    }
                ]
            },

        }
    },
    mounted() {
        this.getChannel();
    },
    methods: {
        current_change: function (currentPage) {
            this.handler.handlerTables.currentPage = currentPage;
        },
        onSubmit(form){
            const h = this.$createElement;
            this.$refs[form].validate(valid => {
                if(valid){
                    this.handler.handlerLoading.fullscreenLoading = true;
                    axios.post(this.handler.handlerURL.addChannel,
                        {
                            name: this.form.name,
                            weights: this.form.weights,
                            cost: this.form.cost,
                            decrease: this.form.decrease
                        })
                    .then(response => {
                        if(response.data !== false){
                            this.$message({
                                message: h("p", null , [
                                    h("i", {style: "color: teal" }, "Registrado"),
                                ]),
                                type: "success",
                            });
                            this.handler.handlerLoading.fullscreenLoading = false;
                            this.getChannel();
                            this.resetForm(form);
                        }
                    })
                }
            })
        },
        getChannel() {
            axios.get(this.handler.handlerURL.getChannel)
            .then(response => {
                this.handler.handlerList.listChannel = response.data;
                this.handler.handlerTables.total = response.data.length;
            })
        },
        resetForm(formName) {
            this.$refs[formName].resetFields();
        },

    }
}
</script>

<style scoped>

</style>
