
<template>
  <div class="card">
    <div class="card-header text-white franja">
      Listado de Envios al Credito
    </div>
    <div class="card-body">
      <!-- <el-button
        class="my-5"
        type="success"
        icon="el-icon-printer"
        @click="download"
      ></el-button> -->

      <el-table
        :data="list_response.documentos"
        style="width: 100%"
        border
        ref="filterInfo"
        @filter-change="datos"
      >
        <el-table-column label="No." type="index" fixed></el-table-column>
        <el-table-column
          label="ENVIO"
          width="100"
          prop="code"
          header-align="center"
          column-key="envio"
          :filters="handlerFilter.empresa"
          :filter-method="filterData"
        ></el-table-column>
        <el-table-column
          label="CLIENTE"
          prop="cliente"
          width="300"
          header-align="center"
        ></el-table-column>
        <el-table-column
          label="TIPO"
          width="100"
          prop="tipo"
          header-align="center"
          column-key="tipo"
          :filters="handlerFilter.correlativo"
          :filter-method="filterCorrelativo"
        ></el-table-column>
        <el-table-column
          label="FECHA"
          width="200"
          prop="fecha"
          header-align="center"
        >
          <template slot-scope="scope">
            <i class="el-icon-time"></i>
            <span style="margin-left: 10px">{{ scope.row.fecha }}</span>
          </template>
        </el-table-column>
        <el-table-column label="TOTAL" header-align="center">
          <template slot-scope="scope">
            {{ scope.row.total | currency }}
          </template>
        </el-table-column>
        <el-table-column label="Operaciones" width="180" header-align="center">
          <!-- <template slot="header">
            <el-input
              v-model="search"
              size="mini"
              placeholder="Type to search"
            />
          </template> -->
          <template slot-scope="scope" class="pl-3">
            <el-button
              @click="showDrawer(scope.row.code)"
              type="primary"
              style="margin-left: 16px"
              plain
              icon="el-icon-full-screen"
            >
            </el-button>
            <el-button
              @click="showDrawerCredit(scope.row.code)"
              type="primary"
              style="margin-left: 16px"
              plain
              icon="el-icon-s-data"
            >
            </el-button>
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
              <el-alert
                title="Sin Documentos"
                type="error"
                description="no se ha cargado ningun archivo"
                show-icon
                v-if="verError"
              >
              </el-alert>
            </el-drawer>
            <el-drawer
              title="Acreditación de Credito"
              :visible.sync="drawerCredit"
              :with-header="true"
              :modal="false"
              size="50%"
              :close-on-press-escape="false"
              :destroy-on-close="true"
              :wrapperClosable="false"
            >
              <el-row :gutter="20">
                <el-col :span="24">
                  <el-table :data="list_response.getCredits">
                    <el-table-column
                      label="Envio"
                      prop="envio"
                    ></el-table-column>
                    <el-table-column label="Saldo">
                      <template slot-scope="scope">
                        {{ scope.row.total | currency }}
                      </template>
                    </el-table-column>
                    <el-table-column
                      label="Estado"
                      prop="estado"
                    ></el-table-column>
                  </el-table>
                </el-col>
              </el-row>
              <el-row>
                <el-col :span="24">
                  <div class="ml-3 mb-3">Banco:</div>
                  <el-select
                    v-model="banco"
                    class="select_width"
                    placeholder="Banco"
                    clearable
                    filterable
                  >
                    <el-option
                      v-for="item in list_response.banksList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"
                    ></el-option>
                  </el-select>
                </el-col>
              </el-row>
              <el-row>
                <el-col :span="24">
                  <div class="ml-3 mb-3">Monto:</div>
                  <el-input placeholder="Monto" v-model="monto"> </el-input>
                </el-col>
              </el-row>
              <el-row>
                <el-col :span="24" class="mt-4">
                  <el-button type="success" plain @click="acreditar()"
                    >Acreditar</el-button
                  >
                </el-col>
              </el-row>
              <el-row :gutter="20">
                <el-col :span="24">
                  <el-table :data="list_response.listInfoCredit">
                    <el-table-column
                      label="Banco"
                      prop="name"
                    ></el-table-column>
                    <el-table-column label="Monto">
                      <template slot-scope="scope">
                        {{ scope.row.monto | currency }}
                      </template>
                    </el-table-column>
                    <el-table-column
                      label="Fecha"
                      prop="fecha"
                    ></el-table-column>
                  </el-table>
                </el-col>
              </el-row>
            </el-drawer>
          </template>
        </el-table-column>
      </el-table>

      <!-- <div style="text-align: left; margin-top: 30px">
        <el-pagination
          background
          layout="total,prev, pager, next"
          :total="total"
          @current-change="current_change"
        ></el-pagination>
      </div> -->
    </div>
  </div>
</template>

<style>
.el-table .warning-row {
  background: oldlace;
}
.el-table .success-row {
  background: #f0f9eb;
}
.formComentario {
  width: 100%;
}
</style>

<script>
export default {
  data() {
    return {
      banco: "",
      monto: "",
      idCredito:"",
      verError: false,
      drawer: false,
      drawerCredit: false,
      datacoment: {
        idDocumento: "",
        idTraslado: "",
      },
      url_list: {
        lista: "deudaCredito",
        dependencias: "dependencias",
        trasladar: "Trasladar",
        info: "infoPDF",
        comentario: "getComentario",
        getFiles: "salePDF",
        getCredits: "getCredits",
        banksList: "banksList",
        acreditar:"acreditar",
        acreditacionesInfo:"acreditacionesInfo",
      },
      list_response: {
        listInfoCredit:[],
        banksList: [],
        getCredits: [],
        documentos: [],
        list_dependencia: [],
        listInfo: [],
        listComentarios: [],
        listFilter: [
          {
            data: "",
          },
        ],
      },
      total: 0,
      currentPage: 1,
      pagesize: 10,
      EditscreenLoading: false,
      dialogo: false,
      idDocumento: 0,
      depActual: 0,
      trasladarBtn: "Sin Enviar",
      form: {
        departamentoId: "",
      },
      rules: {
        departamentoId: [
          {
            require: true,
            message: "Seleccione dirección de traslado",
            trigger: "blur",
          },
        ],
      },
      handlerDialog: {
        preview: {
          title: "Información de Comunicación",
          visible: false,
          width: "50%",
          top: "3vh",
          ver: false,
        },
      },
      handlerFilter: {
        empresa: [],
        correlativo: [],
      },
      currentPagePDF: 0,
      numPages: undefined,
      pageCount: 0,
      src: "",
      show: true,
      search: "",
      query: {},
      data: [],
      total: 0,
      infoAll: false,
      infoFilter: false,
      handlerData: false,
      ver: false,
      columns: [
        { title: "User ID", field: "uid", sortable: true },
        { title: "Username", field: "name" },
        { title: "Age", field: "age", sortable: true },
        { title: "Email", field: "email" },
        { title: "Country", field: "country" },
      ],
    };
  },
  mounted() {
    this.getLista();
    this.banksList();
  },
  watch: {
    query: {
      handler(query) {
        mockData(query).then(({ rows, total }) => {
          this.data = rows;
          this.total = total;
        });
      },
      deep: true,
    },
  },
  methods: {
    acreditar() {
      if (this.monto === "" && this.banco === "") {
        this.$confirm(
          "Ingrese todos los datos faltantes",
          "Acreditación",
          {
            confirmButtonText: "OK",
            type: "warning",
            center: true,
          }
        );
      }else{
        console.log(this.idCredito)
        axios.post(this.url_list.acreditar,{
          credito_id: this.idCredito,
          banks_id: this.banco,
          monto: this.monto,
        }).then(response => {
          if(response.data !== false){
              this.$notify({
                title: "Exitoso",
                message: "Acreditado",
                type: "success",
                duration: 2000,
              });
              this.creditosBank(response.data);
          }
        })
      }
    },

    creditosBank(data){
      axios.post(this.url_list.acreditacionesInfo,{
          id: data
        })
        .then(response => {
          this.list_response.listInfoCredit = response.data;
        })
    },
    banksList() {
      axios.get(this.url_list.banksList).then((response) => {
        this.list_response.banksList = response.data;
      });
    },
    showDrawerCredit(code) {
      this.drawerCredit = true;
      axios
        .post(this.url_list.getCredits, {
          code: code,
        })
        .then((response) => {
          console.log(response.data[0].credito)
          this.list_response.getCredits = response.data;
          this.idCredito = response.data[0].credito;
          this.creditosBank(this.idCredito);
        });
    },
    download() {
      const doc = new jsPDF();
      doc.autoTable({
        html: "#reporte",
        theme: "striped",
        styles: { cellWidth: "auto", fontSize: 8, halign: "center" },
        bodyStyles: { fontSize: 8, halign: "left" },
      });
      doc.save("Informe de Cuentas.pdf");
    },
    tableRowClassName({ row, rowIndex }) {
      if (rowIndex % 2 == 0) {
        return "warning-row";
      } else {
        return "success-row";
      }
      return "";
    },
    getLista() {
      axios.get(this.url_list.lista).then((response) => {
        this.list_response.documentos = response.data;
        this.infoAll = true;
        this.total = response.data.length;
        for (let index = 0; index < this.total; index++) {
          this.handlerFilter.empresa.push({
            text: response.data[index].code,
            value: response.data[index].code,
          });
        }
        this.handlerFilter.correlativo.push({
          text: "Credito",
          value: "Credito",
        });
        this.handlerFilter.correlativo.push({
          text: "Contado",
          value: "Contado",
        });
      });
    },
    getTraslado(id, dependencia) {
      this.dialogo = true;
      this.idDocumento = id;
      this.depActual = dependencia;
      // console.log(id);
    },
    current_change: function (currentPage) {
      this.currentPage = currentPage;
    },
    selectDireccion() {
      axios.get(this.url_list.dependencias).then((response) => {
        this.list_response.list_dependencia = response.data;
      });
    },
    documentTransfer(form) {
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.EditscreenLoading = true;
          axios
            .put(this.url_list.trasladar, {
              Documento: this.idDocumento,
              actual: this.depActual,
              idDireccionTraslado: this.form.departamentoId,
            })
            .then((response) => {
              this.EditscreenLoading = false;
              this.dialogo = false;
              this.getLista();
              // console.log(response.data);
            });
        }
      });
    },
    getComentario(traslado, documento) {
      axios
        .post(this.url_list.comentario, {
          code: traslado,
          documento: documento,
        })
        .then((response) => {
          const status = JSON.parse(response.status);
          const result = response.data;
          if (status == "200") {
            this.list_response.listComentarios = response.data;
            // console.log(response.data);
            // this.total = response.data.length;
          }
        });
    },
    preview(code, traslado, correlativo) {
      this.handlerDialog.preview.visible = true;
      this.datacoment.idDocumento = code;
      this.datacoment.idTraslado = traslado;
      // this.getComentario(code, traslado);
      // this.getNameFiles(correlativo);
    },
    filterData(value, row) {
      // this.list_response.listFilter = [];
      // if(row.empresa === value){
      //   console.log(this.list_response.documentos)
      // }
      return row.code === value;
    },
    filterCorrelativo(value, row) {
      return row.tipo === value;
    },
    datos(row) {
      if (row.envio && row.envio.length > 0) {
        this.list_response.listFilter = [];
        for (let filtro = 0; filtro < row.envio.length; filtro++) {
          for (
            let index = 0;
            index < this.list_response.documentos.length;
            index++
          ) {
            if (
              this.list_response.documentos[index].code === row.envio[filtro]
            ) {
              this.list_response.listFilter.push({
                data: this.list_response.documentos[index],
              });
            }
          }
        }
        this.handlerData = true;
        this.infoAll = false;
        this.infoFilter = true;
      } else if (row.tipo && row.tipo.length > 0) {
        this.list_response.listFilter = [];
        for (let filtro = 0; filtro < row.tipo.length; filtro++) {
          for (
            let index = 0;
            index < this.list_response.documentos.length;
            index++
          ) {
            if (
              this.list_response.documentos[index].tipo === row.tipo[filtro]
            ) {
              this.list_response.listFilter.push({
                data: this.list_response.documentos[index],
              });
            }
          }
        }
        this.handlerData = true;
        this.infoAll = false;
        this.infoFilter = true;
      } else {
        this.handlerData = false;
        this.infoAll = true;
        this.infoFilter = false;
      }
    },
    showDrawer(correlativo) {
      this.drawer = true;
      this.src = "./../pdf/Envio No " + correlativo + ".pdf";
    },
  },
};
</script>