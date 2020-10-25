<template>
    <div class="sale">
        <div class="card">
            <div class="card-header text-white franja">Facturación</div>
            <div class="card-body">
                <el-row :gutter="20" class="mt-2">
                    <el-col :xs="25" :sm="6" :md="19" :xl="19" >
                        <el-row>
                            <el-col :span="12">
                                <span class="nameF"> {{ handlerText.nameF }} </span>
                            </el-col><el-col :span="12">
                                <span class="numberF"> No. </span> <span class="formatF"> {{ handlerText.numberF }} </span>
                            </el-col>
                        </el-row>
                    </el-col>
                    <el-col :xs="25" :sm="6" :md="5" :xl="5">
                        <el-image
                            style="width: 100px; height: 100px"
                            :src="handlerImage.url"
                            :fit="handlerImage.fit"></el-image>
                    </el-col>
                </el-row>
                <el-row >
                    <el-col :xs="25" :sm="6" :md="19" :xl="19" >
                        <el-row>
                            <span class="now">{{ handlerDate.now }}</span>
                        </el-row>
                    </el-col>
                </el-row>
                <el-form ref="form" :model="form" :rules="rules">
                    <el-tabs type="border-card" class="mt-4">
                        <el-tab-pane>
                            <span slot="label"><i class="el-icon-user"></i> Cliente</span>
                            <el-form-item prop="name">
                                <el-input v-model="form.name">
                                    <template slot="prepend">Nombre Cliente:</template>
                                    <el-button slot="append" icon="el-icon-search"></el-button>
                                </el-input>
                            </el-form-item>
                        </el-tab-pane>
                        <el-tab-pane>
                            <span slot="label"><i class="el-icon-user"></i> Detalle</span>
                            <el-row>
                                <el-col :span="18">
                                    <div class="ml-3 mb-3">
                                        Producto:
                                    </div>
                                    <el-select v-model="productItem" class="select_width" placeholder="Nuevo Producto" filterable>
                                    </el-select>
                                </el-col>
                                <el-col :span="5" class="ml-1">
                                    <div class="ml-3 mb-3"> Acción:</div>
                                    <el-button class="ml-2" type="danger" size="medium" :loading="load">Añadir</el-button>
                                </el-col>
                            </el-row>
                            <el-divider></el-divider>
                            <el-row>
                                <el-col :span="24">
                                    <el-table :data="handlerProduct" style="width: 100%" border >
                                        <el-table-column type="index" width="50" label="#"></el-table-column>
                                        <el-table-column width="80" label="Código" prop="code"></el-table-column>
                                        <el-table-column label="Descripción" prop="description"></el-table-column>
                                        <el-table-column width="150" label="Cantidad" prop="counts"></el-table-column>
                                        <el-table-column width="150" label="Precio" prop="price"></el-table-column>
                                        <el-table-column width="150" label="Descuento" prop="discount"></el-table-column>
                                    </el-table>
                                </el-col>
                            </el-row>
                        </el-tab-pane>
                    </el-tabs>
<!--                    <el-tabs type="border-card" class="mt-4">-->
<!--                        <el-tab-pane>-->
<!--                            <span slot="label"><i class="el-icon-user"></i> Detalle</span>-->
<!--                            <el-row>-->
<!--                                <el-col :span="18">-->
<!--                                    <div class="ml-3 mb-3">-->
<!--                                        Producto:-->
<!--                                    </div>-->
<!--                                    <el-select v-model="productItem" class="select_width" placeholder="Nuevo Producto" filterable>-->
<!--                                    </el-select>-->
<!--                                </el-col>-->
<!--                                <el-col :span="5" class="ml-1">-->
<!--                                    <div class="ml-3 mb-3"> Acción:</div>-->
<!--                                    <el-button class="ml-2" type="danger" size="medium" :loading="load">Añadir</el-button>-->
<!--                                </el-col>-->
<!--                            </el-row>-->
<!--                            <el-divider></el-divider>-->
<!--                            <el-row>-->
<!--                                <el-col :span="24">-->
<!--                                    <el-table :data="handlerProduct" style="width: 100%" border >-->
<!--                                        <el-table-column type="index" width="50" label="#"></el-table-column>-->
<!--                                        <el-table-column width="80" label="Código" prop="code"></el-table-column>-->
<!--                                        <el-table-column label="Descripción" prop="description"></el-table-column>-->
<!--                                        <el-table-column width="150" label="Cantidad" prop="counts"></el-table-column>-->
<!--                                        <el-table-column width="150" label="Precio" prop="price"></el-table-column>-->
<!--                                        <el-table-column width="150" label="Descuento" prop="discount"></el-table-column>-->
<!--                                    </el-table>-->
<!--                                </el-col>-->
<!--                            </el-row>-->
<!--                        </el-tab-pane>-->
<!--                    </el-tabs>-->
                </el-form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
        },
        data(){
            return {
                form: {
                    name:""
                },
                rules: {
                  name: [
                      {
                          required: true,
                          message: 'Ingrese Cliente',
                          trigger: 'blur'
                      }
                  ]
                },
                handlerText:{
                    nameF: 'FACTURA',
                    numberF: '000000'
                },
                handlerImage: {
                    url: 'img/logo.jpg',
                    fit: 'scale-down'
                },
                handlerDate:{
                    now: 'Guatemala, 24 de octubre del 2020'
                },
                productItem:"",
                handlerProduct:[
                    {
                        code: 0,
                        description: 'name product',
                        counts: 0,
                        price: 100,
                        discount: 100
                    }
                ]
            }
        }
    };
</script>
