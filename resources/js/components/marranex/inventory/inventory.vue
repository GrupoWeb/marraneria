<template>
  <div>
    <div class="card">
      <div class="card-header text-white franja">Inventario de Productos</div>
      <div class="card-body">
        <el-form ref="form" :model="form" :rules="rules">
          <el-row :gutter="10" class="mt-2">
            <el-col :xs="25" :sm="6" :md="8" :lg="10" :xl="9">
              <el-form-item prop="product">
                <div class="ml-3 mb-3">Producto:</div>
                <el-select
                  v-model="form.product"
                  class="select_width"
                  placeholder="Producto"
                  clearable
                  filterable
                >
                  <el-option
                    v-for="item in handler.handlerResponse.listProduct"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id"
                  ></el-option>
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :xs="25" :sm="6" :md="8" :lg="10" :xl="9">
              <el-form-item prop="measure">
                <div class="ml-3 mb-3">Medida:</div>
                <el-select
                  v-model="form.measure"
                  class="select_width"
                  placeholder="Medida"
                  clearable
                  filterable
                >
                  <el-option
                    v-for="item in handler.handlerResponse.listMeasure"
                    :key="item.id"
                    :label="item.nombre"
                    :value="item.id"
                  ></el-option>
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :xs="25" :sm="6" :md="8" :lg="12" :xl="6">
              <el-form-item prop="count">
                <div class="ml-3 mb-3">Cantidad:</div>
                <el-input v-model="form.count"></el-input>
              </el-form-item>
            </el-col>
          </el-row>
          <el-form-item>
            <el-button
              type="danger"
              @click="onSubmit('form')"
              v-loading.fullscreen.lock="
                handler.handlerLoading.fullscreenLoading
              "
            >
              Guardar
            </el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
    <el-card class="mt-3">
      <el-table
        border
        :empty-text="handler.handlerTables.emptys"
        :data="
          handler.handlerResponse.tableKardex.slice(
            (handler.handlerTables.currentPage - 1) *
              handler.handlerTables.pagesize,
            handler.handlerTables.currentPage * handler.handlerTables.pagesize
          )
        "
      >
        <el-table-column
          label="Producto"
          prop="producto"
          header-align="center"
        ></el-table-column>
        <el-table-column
          label="Medida"
          prop="medida"
          header-align="center"
        ></el-table-column>
        <el-table-column
          label="Cantidad Minima"
          prop="cantidadMinima"
          header-align="center"
        ></el-table-column>
        <el-table-column
          label="Saldo"
          prop="saldo"
          header-align="center"
        ></el-table-column>
      </el-table>
      <div style="text-align: left; margin-top: 30px">
        <el-pagination
          background
          layout="total,prev, pager, next"
          :total="handler.handlerTables.total"
          @current-change="current_change"
        >
        </el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "inventory.vue",
  data() {
    return {
      handler: {
        handlerTables: {
          emptys: "Sin Registros",
          total: 0,
          currentPage: 1,
          pagesize: 10,
        },
        handlerLoading: {
          fullscreenLoading: false,
        },
        handlerURL: {
          addKardex: "tableInventory",
          tableInventory: "tableInventoryTable",
          productList: "productList",
          measureList: "measureList",
        },
        handlerResponse: {
          listProduct: [],
          listMeasure: [],
          tableKardex: [],
        },
      },
      form: {
        product: "",
        measure: "",
        count: "",
        balance: "",
      },
      rules: {
        product: [
          {
            required: true,
            message: "Ingrese el Nombre",
            trigger: "blur",
          },
        ],
        measure: [
          {
            required: true,
            message: "Ingrese el Peso",
            trigger: "blur",
          },
        ],
        count: [
          {
            required: true,
            message: "Ingrese el Costo",
            trigger: "blur",
          },
        ],
      },
    };
  },
  mounted() {
    this.listProduct();
    this.listMeasure();
    this.getKardex();
  },
  methods: {
    current_change: function (currentPage) {
      this.handler.handlerTables.currentPage = currentPage;
    },
    onSubmit(form) {
      const h = this.$createElement;
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.handler.handlerLoading.fullscreenLoading = true;
          axios
            .post(this.handler.handlerURL.addKardex, {
              product: this.form.product,
              measure: this.form.measure,
              count: this.form.count,
            })
            .then((response) => {
              if (response.data !== false) {
                this.$message({
                  message: h("p", null, [
                    h("i", { style: "color: teal" }, "Registrado"),
                  ]),
                  type: "success",
                });
                this.handler.handlerLoading.fullscreenLoading = false;
                this.getKardex();
                this.resetForm(form);
              }
            });
        }
      });
    },
    getKardex(){
      axios.get(this.handler.handlerURL.tableInventory)
        .then(response => {
          this.handler.handlerResponse.tableKardex = response.data
        })  
    },
    listProduct() {
      axios.get(this.handler.handlerURL.productList).then((response) => {
        this.handler.handlerResponse.listProduct = response.data;
      });
    },
    listMeasure() {
      axios.get(this.handler.handlerURL.measureList).then((response) => {
        this.handler.handlerResponse.listMeasure = response.data;
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
  },
};
</script>

<style scoped>
</style>
