<template>
  <div>
    <div class="card">
      <div class="card-header text-white franja">Catálogo de Clientes</div>
      <div class="card-body">
        <el-form ref="form" :model="form" :rules="rules">
          <el-row :gutter="10" class="mt-2">
            <el-col :xs="25" :sm="6" :md="8" :lg="12" :xl="12">
              <el-form-item >
                <el-input v-model="form.nit">
                  <template slot="prepend">NIT:</template>
                </el-input>
              </el-form-item>
            </el-col>
            <el-col :xs="25" :sm="6" :md="8" :lg="12" :xl="12">
              <el-form-item >
                <el-input v-model="form.dpi">
                  <template slot="prepend">DPI:</template>
                </el-input>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="10" class="mt-2">
            <el-col :xs="25" :sm="6" :md="8" :lg="12" :xl="12">
              <el-form-item prop="name">
                <el-input v-model="form.name">
                  <template slot="prepend">NOMBRE:</template>
                </el-input>
              </el-form-item>
            </el-col>
            <el-col :xs="25" :sm="6" :md="8" :lg="12" :xl="12">
              <el-form-item prop="surname">
                <el-input v-model="form.surname">
                  <template slot="prepend">APELLIDO:</template>
                </el-input>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="10" class="mt-2">
            <el-col :xs="25" :sm="6" :md="8" :lg="12" :xl="12">
              <el-form-item >
                <el-input v-model="form.address">
                  <template slot="prepend">DIRECCIÓN:</template>
                </el-input>
              </el-form-item>
            </el-col>
            <el-col :xs="25" :sm="6" :md="8" :lg="12" :xl="12">
              <el-form-item >
                <el-input v-model="form.phone">
                  <template slot="prepend">TELÉFONO:</template>
                </el-input>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="10" class="mt-2">
            <el-col :xs="25" :sm="6" :md="8" :lg="12" :xl="12">
              <el-form-item>
                <el-input v-model="form.company">
                  <template slot="prepend">EMPRESA:</template>
                </el-input>
              </el-form-item>
            </el-col>
            <el-col :xs="25" :sm="6" :md="8" :lg="12" :xl="12">
              <el-form-item >
                <el-input v-model="form.contact">
                  <template slot="prepend">% DESCUENTO:</template>
                </el-input>
              </el-form-item>
            </el-col>
          </el-row>

          <el-form-item>
            <el-button
              type="danger"
              @click="onSubmit('form')"
              v-loading.fullscreen.lock="fullscreenLoading"
              >Guardar</el-button
            >
          </el-form-item>
        </el-form>
        <el-table
          :data="
            response_data.listClient.slice(
              (currentPage - 1) * pagesize,
              currentPage * pagesize
            ).filter(data => !search || data.name.toLowerCase().includes(search.toLowerCase()))
          "
          style="width: 100%"
          border
        >
          <el-table-column
            label="No."
            header-align="center"
            type="index"
          ></el-table-column>
          <el-table-column
            label="NIT"
            header-align="center"
            prop="nit"
          ></el-table-column>
          <el-table-column
            label="NOMBRE"
            header-align="center"
            prop="name"
          ></el-table-column>
          <el-table-column
            label="APELLIDO"
            header-align="center"
            prop="surname"
          ></el-table-column>
          <el-table-column
            label="EMPRESA"
            header-align="center"
            prop="company"
          ></el-table-column>
          <el-table-column
            label="DIRECCIÓN"
            header-align="center"
            prop="address"
          ></el-table-column>
          <el-table-column
            label="DPI"
            header-align="center"
            prop="dpi"
          ></el-table-column>
          <el-table-column
            label="TELÉFONO"
            header-align="center"
            prop="phone"
          ></el-table-column>
          <el-table-column
            label="Operaciones"
            header-align="center"
            width="200"
          >
            <template slot="header" slot-scope="scope">
              <el-input
                v-model="search"
                size="mini"
                placeholder="Buscar"
              />
            </template>
            <template slot-scope="scope">
              <el-button
                size="mini"
                icon="el-icon-delete-solid"
                type="danger"
                @click="handleEdit(scope.row.id)"
              ></el-button>
            </template>
          </el-table-column>
        </el-table>
        <div style="text-align: left; margin-top: 30px">
          <el-pagination
            background
            layout="total,prev, pager, next"
            :total="handlerTable.total"
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
            ref="form"
            :rule="rules"
            label-width="150px"
          >
            <el-form-item label="Nombre Anterior">
              <label style="color: red">
                {{ descripcion_seleccion }}
              </label>
            </el-form-item>
            <el-form-item label="Nuevo Nombre">
              <el-input v-model="formEdit.name"></el-input>
            </el-form-item>
            <el-form-item>
              <el-button
                type="primary"
                @click="editProduct()"
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
        handlerTable: {
            total:0,
        },
      search: '',
      url_data: {
        addClient: "client",
        listClient: "client",
        deleteCliente: "client",
      },
      response_data: {
        listClient: [],
      },
      total: 0,
      currentPage: 1,
      pagesize: 10,
      form: {
        nit: "",
        name: "",
        surname: "",
        dpi: "",
        address: "",
        phone: "",
        company: "",
        contact: "",
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
        surname: [
          {
            required: true,
            message: "Este campo no puede ser vacio",
            trigger: "blur",
          },
        ],
      },
      fullscreenLoading: false,
      EditscreenLoading: false,
      dialogo: false,
      id_seleccion: 0,
      descripcion_seleccion: "",
    };
  },
  mounted() {
    this.getClient();
  },
  methods: {
    onSubmit(form) {
      const h = this.$createElement;
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.fullscreenLoading = true;
          axios
            .post(this.url_data.addClient, {
              nit: this.form.nit,
              name: this.form.name,
              surname: this.form.surname,
              dpi: this.form.dpi,
              address: this.form.address,
              phone: this.form.phone,
              company: this.form.company,
              contact: this.form.contact,
            })
            .then((response) => {
              const status = JSON.parse(response.status);
              if (status == "200" && response.data != false) {
                this.$message({
                  message: h("p", null, [
                    h("i", { style: "color: teal" }, "Agregado!"),
                  ]),
                  type: "success",
                });
                this.fullscreenLoading = false;
                this.resetForm(form);
                this.getClient();
                // this.getAll();
              }
            });
        } else {
          this.$message.error({
            message: h("p", null, [
              h("i", { style: "color: red" }, "Ingrese un Nombre"),
            ]),
          });
          return false;
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
    getClient() {
      axios.get(this.url_data.listClient).then((response) => {
        this.response_data.listClient = response.data;
          this.handlerTable.total = response.data.length;
      });
    },

    current_change: function (currentPage) {
      this.currentPage = currentPage;
    },
    handleEdit(id) {
      this.$confirm("Desea eliminar a Cliente?", "Inactivar Cliente", {
        confirmButtonText: "SI",
        cancelButtonText: "NO",
        type: "warning",
      })
        .then(() => {
          axios
            .put(this.url_data.deleteCliente, {
              id: id,
            })
            .then((response) => {
              const status = response.data.status;
              if (response.data === 1) {
                this.$message({
                  type: "success",
                  message: "Cliente Eliminado",
                });

                this.getClient();
              }
            })
            .catch((err) => {
              this.$message({
                type: "danger",
                message: "Error en Servidor",
              });
            });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "Cancelado",
          });
        });
    },
    handleDelete(code) {
      const h = this.$createElement;
      let url = "deleteProductById";
      this.EditscreenLoading = true;
      axios
        .post(url, {
          id: code,
        })
        .then((response) => {
          const status = JSON.parse(response.status);
          if (status == "200") {
            this.$message({
              message: h("p", null, [
                h("i", { style: "color: teal" }, "Producto Eliminado"),
              ]),
              type: "success",
            });
            this.EditscreenLoading = false;
            this.formEdit.name = "";
            this.dialogo = false;
            this.getAll();
          }
        });
    },
  },
};
</script>
