<template>
  <div>
    <div class="card">
      <div class="card-header text-white franja">Catálogo de Productos</div>
      <div class="card-body">
        <el-form ref="form" :model="form" :rules="rules" label-width="150px">
          <el-form-item label="Nombre:" prop="name">
            <el-input v-model="form.name"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button
              type="primary"
              @click="onSubmit('form')"
              v-loading.fullscreen.lock="fullscreenLoading"
              >Guardar</el-button
            >
          </el-form-item>
        </el-form>
        <el-table
          :data="
            response_data.listGet.slice(
              (currentPage - 1) * pagesize,
              currentPage * pagesize
            )
          "
          style="width: 100%"
        >
          <el-table-column label="No." type="index"></el-table-column>
          <el-table-column label="Producto" prop="name"></el-table-column>
          <el-table-column label="Acciones" width="200">
            <template slot-scope="scope">
              <el-button
                size="mini"
                @click="
                  handleEdit(scope.row.id)
                "
                >Editar</el-button
              >
              <el-button
                size="mini"
                type="danger"
                @click="handleDelete(scope.row.id)"
                v-loading.fullscreen.lock="EditscreenLoading"
                >Eliminar</el-button
              >
            </template>
          </el-table-column>
        </el-table>
        <div style="text-align: left; margin-top: 30px">
          <el-pagination
            background
            layout="total,prev, pager, next"
            :total="total"
            @current-change="current_change"
          ></el-pagination>
        </div>
        <el-dialog
          title="Modificación"
          :visible.sync="dialogo"
          width="70%"
          top="3vh"
          destroy-on-close
        >
          <el-form
            :inline="false"
            :model="formEdit"
            ref="formEdit"
            :rule="rule"
            label-width="150px"
          >
            <el-form-item label="Nombre:">
              <el-input v-model="formEdit.name"></el-input>
            </el-form-item>
            <el-form-item>
              <el-button
                type="primary"
                @click="editProduct('formEdit')"
                v-loading.fullscreen.lock="EditscreenLoading"
                >Guardar</el-button
              >
            </el-form-item>
          </el-form>
        </el-dialog>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      url_data: {
        listProduct: "product",
        getList: "productList",
        editProduct: "product",
        delete: "delete",
      },
      response_data: {
        listProduct: [],
        listGet: [],
      },
      total: 0,
      currentPage: 1,
      pagesize: 10,
      form: {
        name: "",
      },
      formEdit: {
        name: "",
      },
      listProduct: [],
      rules: {
        name: [
          {
            required: true,
            message: "Este campo no puede ser vacio",
            trigger: "blur",
          },
        ],
      },
      rule: {
        name: [
          {
            required: true,
            message: "Este campo no puede ser vacio",
            trigger: "blur",

          }
        ]
      },
      fullscreenLoading: false,
      EditscreenLoading: false,
      dialogo: false,
      id_seleccion: 0,
      descripcion_seleccion: "",
    };
  },
  mounted() {
    this.getProduct();
  },
  methods: {
    onSubmit(form) {
      const h = this.$createElement;
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.fullscreenLoading = true;
          let url = "product";
          axios
            .post(url, {
              name: this.form.name,
            })
            .then((response) => {
              const status = JSON.parse(response.status);
              if (status == "200") {
                this.$message({
                  message: h("p", null, [
                    h("i", { style: "color: teal" }, "Producto Agregado!"),
                  ]),
                  type: "success",
                });
                this.fullscreenLoading = false;
                this.form.name = "";
                this.getProduct();
              }
            });
        } else {
          this.$message.error({
            message: h("p", null, [
              h("i", { style: "color: red" }, "Ingrese Producto"),
            ]),
          });
          return false;
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
    getProduct() {
      axios.get(this.url_data.getList).then((response) => {
        this.response_data.listGet = response.data;
        
      });
    },
 
    editProduct(form) {
        const h = this.$createElement;
        this.EditscreenLoading = true;
        if(this.formEdit.name !== ""){
          axios
              .put(this.url_data.editProduct, {
                  id: this.id_seleccion,
                  name: this.formEdit.name
              })
              .then(response => {
                  
                  if (response.data === 1) {
                      this.$message({
                          message: h("p", null, [
                              h(
                                  "i",
                                  { style: "color: teal" },
                                  "Cambio realizado con exito!"
                              )
                          ]),
                          type: "success"
                      });
                      this.EditscreenLoading = false;
                      this.formEdit.name = "";
                      this.dialogo = false;
                      this.getProduct();
                  }
              });
        }else{
          this.EditscreenLoading = false;
          this.$message({
              message: h("p", null, [
                  h(
                      "i",
                      { style: "color: teal" },
                      "Ingrese un dato"
                  )
              ]),
              type: "danger"
          });
        }
    },
    current_change: function (currentPage) {
      this.currentPage = currentPage;
    },
    handleEdit(id) {
      this.dialogo = true;
      this.id_seleccion = id;
      
    },
    handleDelete(code) {
      
      const h = this.$createElement;
      this.EditscreenLoading = true;
      axios
        .put(this.url_data.delete, {
          id: code,
        })
        .then((response) => {
          
          if (response.data === 1) {
            this.$message({
              message: h("p", null, [
                h("i", { style: "color: teal" }, "Producto Eliminado"),
              ]),
              type: "success",
            });
            this.EditscreenLoading = false;
            this.formEdit.name = "";
            this.dialogo = false;
            this.getProduct();
          }
        });
    },
  },
};
</script>
