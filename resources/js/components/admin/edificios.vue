<template>
  <div>
    <div class="card">
      <div class="card-header text-white bg-primary">Catálogo de Edificios</div>
      <div class="card-body">
        <el-form ref="form" :model="form" :rules="rules" label-width="20px">
          <el-row :gutter="10" class="mt-2">
            <el-col :xs="25" :sm="6" :md="8" :lg="20" :xl="12">
              <el-form-item prop="nombre">
                <el-input v-model="form.nombre">
                  <template slot="prepend">Nombre:</template>
                </el-input>
              </el-form-item>
            </el-col>
            <el-col :xs="25" :sm="6" :md="8" :lg="20" :xl="12">
              <el-form-item prop="direccion">
                <el-input v-model="form.direccion">
                  <template slot="prepend">Dirección:</template>
                </el-input>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="10" class="mt-2">
            <el-col :xs="25" :sm="6" :md="8" :lg="20" :xl="12">
              <el-form-item prop="telefono">
                <el-input v-model="form.telefono">
                  <template slot="prepend">Telefono:</template>
                </el-input>
              </el-form-item>
            </el-col>
            <el-col :xs="25" :sm="6" :md="8" :lg="20" :xl="12">
              <el-form-item prop="contacto">
                <el-input v-model="form.contacto">
                  <template slot="prepend">Contaco:</template>
                </el-input>
              </el-form-item>
            </el-col>
          </el-row>
          <el-form-item>
            <el-button
              type="primary"
              @click="onSubmit('form')"
              v-loading.fullscreen.lock="load"
            >Guardar</el-button>
          </el-form-item>
        </el-form>
        <el-table :data="response_list.ListaEdificio">
          <el-table-column label="No." type="index"></el-table-column>
          <el-table-column label="Nombre:" prop="nombre"></el-table-column>
          <el-table-column label="Dirección:" prop="direccion"></el-table-column>
          <el-table-column label="Teléfono:" prop="telefono"></el-table-column>
          <el-table-column label="Contacto:" prop="contacto"></el-table-column>
          <el-table-column label="Operaciones" width="200">
            <template slot-scope="scope">
              <el-button size="mini" @click="handleEdit(scope.row.id)">Editar</el-button>
            </template>
          </el-table-column>
        </el-table>
        <div style="text-align: left;margin-top: 30px;">
          <el-pagination
            background
            layout="total,prev, pager, next"
            :total="pagination_conf.total"
            @current-change="current_change"
          ></el-pagination>
          <el-dialog
            title="Modificación"
            :visible.sync="dialogo"
            width="70%"
            top="3vh"
            destroy-on-close
          >
            <el-form
              :inline="false"
              :model="edicion"
              ref="edicion"
              :rule="rules"
              label-width="150px"
            >
              <el-form-item label="Nuevo Teléfono" prop="telefono">
                <el-input v-model="edicion.telefono"></el-input>
              </el-form-item>
              <el-form-item>
                <el-button
                  type="primary"
                  @click="editEdificio('edicion')"
                  v-loading.fullscreen.lock="EditscreenLoading"
                >Guardar</el-button>
              </el-form-item>
            </el-form>
          </el-dialog>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      pagination_conf: {
        total: 0,
        dialogo: false,
      },
      url: {
        setEdificio: "setEdificio",
        listaEdificio: "listaEdificios",
        edicion: "edicionEdificio",
      },
      form: {
        nombre: "",
        direccion: "",
        telefono: "",
        contacto: "",
      },
      edicion: {
        telefono: "",
      },

      rules: {
        nombre: [
          {
            required: true,
            message: "No puede estar vacio",
            trigger: "blur",
          },
        ],
        direccion: [
          {
            required: true,
            message: "No puede estar vacio",
            trigger: "blur",
          },
        ],
        telefono: [
          {
            required: true,
            message: "No puede estar vacio",
            trigger: "blur",
          },
        ],
        contacto: [
          {
            required: true,
            message: "No puede estar vacio",
            trigger: "blur",
          },
        ],
        telefono: [
          {
            required: true,
            message: "No puede estar vacio",
            trigger: "blur",
          },
        ],
      },
      load: false,
      EditscreenLoading: false,
      dialogo: false,
      id_seleccion: 0,
      response_list: {
        ListaEdificio: [],
      },
    };
  },
  mounted() {
    this.lista();
  },
  methods: {
    onSubmit(form) {
    
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.load = true;
          axios
            .post(this.url.setEdificio, {
              name: this.form.nombre,
              address: this.form.direccion,
              tel: this.form.telefono,
              cont: this.form.contacto,
            })
            .then((response) => {
              const status = JSON.parse(response.status);
              if (status == "200" && response.data != false) {
                this.$notify({
                  title: "Operación Exitosa",
                  message: "Se almaceno el edificio",
                  type: "success",
                });
                this.load = false;
                this.lista();
                this.$refs[form].resetFields();
              }
            });
        }
      });
    },
    lista() {
      axios.get(this.url.listaEdificio).then((response) => {
        this.response_list.ListaEdificio = response.data;
        this.pagination_conf.total = response.data.length;
      });
    },
    current_change: function (currentPage) {
      this.currentPage = currentPage;
    },
    handleEdit(producto) {
      this.dialogo = true;
      this.id_seleccion = producto;
    },
    editEdificio(form) {
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.EditscreenLoading = true;
          axios
            .put(this.url.edicion, {
              code: this.id_seleccion,
              tel: this.edicion.telefono,
            })
            .then((response) => {
                  console.log(response.data)
              const status = JSON.parse(response.status);
              if (status == "200" && response.data != false) {
                this.$notify({
                  title: "Operación Exitosa",
                  message: "Se modifico el telefono",
                  type: "success",
                });
                this.EditscreenLoading = false;
                this.dialogo = false;
                this.lista();
                this.$refs[form].resetFields();
              }
            });
        }
      });
    },
  },
};
</script>