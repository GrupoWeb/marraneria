<template>
  <div class="sale">
    <div class="card">
      <div class="card-header text-white franja">Envios</div>
      <div class="card-body">
        <el-row :gutter="20" class="mt-2">
          <el-col :xs="25" :sm="6" :md="19" :xl="19">
            <el-row>
              <el-col :span="3">
                <span class="nameF"> {{ handledText.nameF }} </span> </el-col
              >
              
            </el-row>
          </el-col>
          <el-col :xs="25" :sm="6" :md="5" :xl="5">
            <el-image
              style="width: 100px; height: 100px"
              :src="handledImage.url"
              :fit="handledImage.fit"
            ></el-image>
          </el-col>
        </el-row>
        <el-row>
          <el-col :xs="25" :sm="6" :md="15" :xl="19">
            <el-row>
              <span class="now">{{ handledDate.now }}</span>
            </el-row>
          </el-col>
          <el-col :xs="25" :sm="6" :md="6" :xl="4">
            <span class="total">TOTAL: {{ totalCurrent | currency }}</span>
          </el-col>
        </el-row>
        <el-form ref="form" :model="form" :rules="rules">
          <el-tabs type="border-card" class="mt-4">
            <el-tab-pane>
              <span slot="label"><i class="el-icon-user"></i> Cliente</span>
              <el-form-item prop="name">
                <!-- <el-input v-model="form.name"> -->
                <div class="ml-3 mb-3">Cliente:</div>
                <el-select
                  v-model="form.name"
                  class="select_width"
                  placeholder="Producto"
                  clearable
                  filterable
                  @change="clienteInput()"
                >
                  <el-option
                    v-for="item in handledResponse.listClient"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id"
                  ></el-option>
                </el-select>
              </el-form-item>
              <div v-if="showInfo" v-loading="loading">
                <el-form-item label="Tipo">
                  <el-radio-group v-model="form.type">
                    <el-radio label="Credito"></el-radio>
                    <el-radio label="Contado"></el-radio>
                  </el-radio-group>
                </el-form-item>
                <div class="ml-3 mb-3">Informacion de Cliente:</div>
                <el-table :data="handledResponse.tableClientById" border>
                  <el-table-column prop="nit" label="NIT"></el-table-column>
                  <el-table-column prop="name" label="NOMBRE"></el-table-column>
                  <el-table-column
                    prop="surname"
                    label="APELLIDO"
                  ></el-table-column>
                  <el-table-column
                    prop="address"
                    label="DIRECCIÓN"
                  ></el-table-column>
                  <el-table-column
                    prop="phone"
                    label="TELÉFONO"
                  ></el-table-column>
                  <el-table-column
                    prop="company"
                    label="EMPRESA"
                  ></el-table-column>
                </el-table>
              </div>
            </el-tab-pane>
            <el-tab-pane>
              <span slot="label"
                ><i class="el-icon-edit-outline"></i> Detalle</span
              >
              <el-row :gutter="10">
                <el-col :xs="25" :sm="6" :md="8" :lg="10" :xl="10">
                  <div class="ml-3 mb-3">Producto:</div>
                  <el-select
                    v-model="handledItemTable.productItem"
                    class="select_width"
                    placeholder="Nuevo Producto"
                    clearable
                    filterable
                  >
                    <el-option
                      v-for="item in handledResponse.listProduct"
                      :key="item.codeProduct"
                      :label="item.producto"
                      :value="item.codeProduct"
                    ></el-option>
                  </el-select>
                </el-col>
                <el-col :xs="25" :sm="6" :md="8" :lg="3" :xl="3">
                  <div class="ml-3 mb-3">Cantidad:</div>
                  <el-input
                    v-model="handledItemTable.countItem"
                    placeholder="Cantidad"
                  ></el-input>
                </el-col>
                <el-col :xs="25" :sm="6" :md="8" :lg="3" :xl="3">
                  <div class="ml-3 mb-3">Precio:</div>
                  <el-input
                    v-model="handledItemTable.priceItem"
                    placeholder="Q 100.00"
                  ></el-input>
                </el-col>
                <el-col :xs="25" :sm="6" :md="8" :lg="3" :xl="3">
                  <div class="ml-3 mb-3">Descuento:</div>
                  <el-input
                    v-model="handledItemTable.discountItem"
                    placeholder="Q 50.00"
                  ></el-input>
                </el-col>
                <el-col :span="4" class="ml-1">
                  <div class="ml-3 mb-3">Acción:</div>
                  <el-button
                    class="ml-2"
                    type="primary"
                    size="small"
                    :loading="addload"
                    @click="addItem"
                    :disabled="handledButton"
                    >Añadir</el-button
                  >
                </el-col>
              </el-row>
              <el-divider></el-divider>
              <el-row>
                <el-col :span="24">
                  <el-table
                    ref="multipleTable"
                    :data="handledProduct"
                    style="width: 100%"
                    border
                    stripe
                  >
                    <el-table-column width="60">
                      <template slot-scope="scope">
                        <el-button
                          type="danger"
                          circle
                          size="mini"
                          icon="el-icon-delete"
                          @click="
                            handledDelete(
                              scope.$index,
                              scope.row,
                              scope.row.subtotal
                            )
                          "
                        ></el-button>
                      </template>
                    </el-table-column>
                    <el-table-column
                      type="index"
                      width="50"
                      label="#"
                    ></el-table-column>
                    <el-table-column
                      width="80"
                      label="Código"
                      prop="code"
                    ></el-table-column>
                    <el-table-column
                      label="Descripción"
                      prop="description"
                    ></el-table-column>
                    <el-table-column
                      width="150"
                      label="Cantidad"
                      prop="counts"
                    ></el-table-column>
                    <el-table-column width="150" label="Precio">
                      <template slot-scope="scope">
                        {{ scope.row.price | currency }}
                      </template>
                    </el-table-column>
                    <el-table-column width="150" label="Descuento">
                      <template slot-scope="scope">
                        {{ scope.row.discount | currency }}
                      </template>
                    </el-table-column>
                    <el-table-column
                      width="150"
                      label="Subtotal"
                      prop="subtotal"
                    >
                      <template slot-scope="scope">
                        {{ scope.row.subtotal | currency }}
                      </template>
                    </el-table-column>
                  </el-table>
                </el-col>
              </el-row>
            </el-tab-pane>
          </el-tabs>
          <el-form-item>
            <el-button type="primary" class="mt-3" @click="onSubmit('form')" :disabled="handledButton" 
                v-loading.fullscreen.lock="loadingAll"
                element-loading-text="Loading..."
                element-loading-spinner="el-icon-loading"
                element-loading-background="rgba(0, 0, 0, 0.8)"
              >Facturar</el-button
            >
            <el-button type="success"  @click="showPDF()" v-show="pdfB"  
              >PDF</el-button
            >
          </el-form-item>

        </el-form>
                    <el-drawer
              title="Documento"
              :visible.sync="drawer"
              :with-header="false"
              :modal="false"
            >
              <embed
                :src="src"
                type="application/pdf"
                width="100%"
                height="100%"
              />
              <!-- <el-alert
                title="Sin Documentos"
                type="error"
                description="no se ha cargado ningun archivo"
                show-icon
                v-if="verError"
              >
              </el-alert> -->
            </el-drawer>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["envio"],
  data() {
    return {
      pdfB: false,
      drawer: false,
      src: "",
      showInfo: false,
      loading: true,
      loadingAll: false,
      form: {
        name: "",
        type: "",
        
      },
      rules: {
        name: [
          {
            required: true,
            message: "Ingrese Cliente",
            trigger: "blur",
          },
        ],
      },
      handledText: {
        nameF: "ENVIO",
        numberF: "000000",
      },
      handledImage: {
        url: "img/logo.jpg",
        fit: "scale-down",
      },
      handledDate: {
        now: "Guatemala, 24 de octubre del 2020",
        mes: "",
      },
      handledProduct: [],
      handledItemTable: {
        productItem: "",
        countItem: "",
        priceItem: "",
        discountItem: "",
        subtotal: 0,
      },
      multipleSelection: [],
      handledList: {
        productList: "tableInventory",
        // productByID: "productByID",
        productByID: "InventoryProductById",
        getClient: "client",
        clientById: "clientById",
        getNumberShipping: "getNumberShipping/",
        addSales:"addSales",
        salePDF: "salePDF"
      },
      handledResponse: {
        listProduct: [],
        listClient: [],
        tableClientById: [],
      },
      addload: false,
      totalCurrent: 0,
      table: "sales",
      handledButton: false,


    };
  },
  mounted() {
    this.listProduct();
    this.getClientes();
    this.nowData();
    // this.getShipping();
    this.handledText.numberF = this.envio;
    
  },
  methods: {
    showPDF(){
      this.drawer = true;
    },
    onSubmit(form) {
      const h = this.$createElement;
      this.$refs[form].validate((valid) => {
        if (valid) {
          // this.handler.handlerLoading.fullscreenLoading = true;
          this.loadingAll = true;
          axios
            .post(this.handledList.addSales, {
              client: this.form.name,
              type: this.form.type,
              product: this.handledProduct,
              total: this.totalCurrent,
              envio: this.handledText.numberF,
            })
            .then((response) => {
              console.log(response.data)
              axios.post(this.handledList.salePDF,{
                id: this.handledText.numberF
              }).then(response => {
                this.drawer = true;
                this.src = "./../pdf/"+ response.data;
                this.pdfB = true;
                
                  this.$message({
                    message: h("p", null, [
                      h("i", { style: "color: teal" }, "Registrado"),
                    ]),
                    type: "success",
                  });
                  this.loadingAll = false;
                  this.handledButton = true;
                

              })
            });
        }
      });
    },
    nowData() {
      const hoy = new Date();
      const dia = hoy.getDate();
      const mes = hoy.getMonth();
      const anio = hoy.getFullYear();

      switch (mes) {
        case 0:
          this.handledDate.mes = "Enero";
          break;
        case 1:
          this.handledDate.mes = "Febrero";
          break;
        case 2:
          this.handledDate.mes = "Marzo";
          break;
        case 3:
          this.handledDate.mes = "Abril";
          break;
        case 4:
          this.handledDate.mes = "Mayo";
          break;
        case 5:
          this.handledDate.mes = "Junio";
          break;
        case 6:
          this.handledDate.mes = "Julio";
          break;
        case 7:
          this.handledDate.mes = "Agosto";
          break;
        case 8:
          this.handledDate.mes = "Septiembre";
          break;
        case 9:
          this.handledDate.mes = "Octubre";
          break;
        case 10:
          this.handledDate.mes = "Noviembre";
          break;
        case 11:
          this.handledDate.mes = "Diciembre";
          break;
      }

      this.handledDate.now =
        "Guatemala, " + dia + " de " + this.handledDate.mes + " del " + anio;
    },
    clienteInput() {
      axios
        .post(this.handledList.clientById, { id: this.form.name })
        .then((response) => {
          this.handledResponse.tableClientById = response.data;
          this.showInfo = true;
          this.loading = false;
        });
    },
    getClientes() {
      axios.get(this.handledList.getClient).then((response) => {
        this.handledResponse.listClient = response.data;
      });
    },
    listProduct() {
      axios.get(this.handledList.productList).then((response) => {
        this.handledResponse.listProduct = response.data;
        console.log(response.data)
      });
    },
    handledDelete(row, subtotal) {
      this.totalCurrent = this.totalCurrent - subtotal.subtotal;
      this.handledProduct.splice(row, 1);
      this.$notify({
        title: "Factura",
        message: "Registro Eliminado, con exito!",
        position: "bottom-right",
      });
    },
    addItem() {
      this.load = true;
      if (
        this.handledItemTable.productItem === "" ||
        this.handledItemTable.countItem === "" ||
        this.handledItemTable.priceItem === "" ||
        this.handledItemTable.discountItem === ""
      ) {
        this.$notify.error({
          title: "Datos Vacios",
          message: "Por favor complete los campos requeridos",
          duration: 2000,
        });
        this.load = false;
      } else {
        const h = this.$createElement;
        axios
          .post(this.handledList.productByID, {
            id: this.handledItemTable.productItem,
          })
          .then((response) => {
            console.log(response.data)

            if (response.data.length > 0) {
              this.handledProduct.push({
                code: response.data[0].id,
                description: response.data[0].name,
                counts: this.handledItemTable.countItem,
                price: this.handledItemTable.priceItem,
                discount: this.handledItemTable.discountItem,
                subtotal:
                  this.handledItemTable.priceItem *
                    this.handledItemTable.countItem -
                  this.handledItemTable.discountItem,
              });
  
              this.totalCurrent =
                this.totalCurrent +
                this.handledItemTable.priceItem *
                  this.handledItemTable.countItem -
                this.handledItemTable.discountItem;
              this.$notify({
                title: "Exitoso",
                message: "Producto Añadido",
                type: "success",
                duration: 2000,
              });
              this.load = false;
              this.handledItemTable.productItem = "";
              this.handledItemTable.countItem = "";
              this.handledItemTable.priceItem = "";
              this.handledItemTable.discountItem = "";
            }else{
              this.$confirm('No se cuenta con el suficiente inventario para este producto, cuenta con menos o igual a 2', 'Saldo de Producto', {
                confirmButtonText: 'OK',
                type: 'warning',
                center: true
              })
            }
          });
      }
    },
    getSummaries(param) {
      const { columns, data } = param;

      const sums = [];
      columns.forEach((column, index) => {
        if (index === 6) {
          sums[index] = ":";
          return;
        }

        const values = data.map((item) => Number(item[column.property]));
        if (!values.every((value) => isNaN(value)) && index === 7) {
          sums[index] =
            "" +
            values.reduce((prev, curr) => {
              const value = Number(curr);
              console.log("curr ", curr, "prev ", prev);
              if (!isNaN(value)) {
                // var result = (prev + curr) | currency;
                this.totalCurrent = prev + curr;
                return "";
              } else {
                this.totalCurrent = prev;
                return "";
              }
            }, 0);
        } else {
          sums[index] = "";
        }
      });

      return sums;
    },
  },
};
</script>
