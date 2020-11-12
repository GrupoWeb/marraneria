
<template>
  <div class="card">
    <div class="card-header text-white franja">Listado de Envios</div>
    <div class="card-body">
      <!-- <el-button
        class="my-5"
        type="success"
        icon="el-icon-printer"
        @click="download"
      ></el-button> -->

      <el-table
        :data="
          list_response.documentos
        "
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
        <el-table-column
          label="TOTAL"
          
          header-align="center"
        >
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
    <div v-show="ver">
      <div v-if="handlerData === false">
        <table class="table" id="reporte" v-show="infoAll">
          <thead>
            <tr>
              <td>ENVIO</td>
              <td>CLIENTE</td>
              <td>TIPO</td>
              <td>FECHA</td>
              <td>TOTAL</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(index, x) in list_response.documentos" :key="x">
              <!-- {{ index }} -->
              <td>{{ index.code }}</td>
              <td>{{ index.cliente }}</td>
              <td>{{ index.tipo }}</td>
              <td>{{ index.fecha }}</td>
              <td>{{ index.total }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div else>
        <table class="table" id="reporte" v-show="infoFilter">
          <thead>
            <tr>
              <td>ENVIO</td>
              <td>CLIENTE</td>
              <td>TIPO</td>
              <td>FECHA</td>
              <td>TOTAL</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(index, x) in list_response.listFilter" :key="x">
              <!-- {{ index }} -->
              <td>{{ index.data.code }}</td>
              <td>{{ index.data.cliente }}</td>
              <td>{{ index.data.tipo }}</td>
              <td>{{ index.data.fecha }}</td>
              <td>{{ index.data.total }}</td>
            </tr>
          </tbody>
        </table>
      </div>
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
      verError: false,
      drawer: false,
      datacoment: {
        idDocumento: "",
        idTraslado: "",
      },
      url_list: {
        lista: "getSales",
        dependencias: "dependencias",
        trasladar: "Trasladar",
        info: "infoPDF",
        comentario: "getComentario",
        getFiles: "salePDF",
      },
      list_response: {
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
    download() {
      const doc = new jsPDF();
      // doc.fromHTML(document.getElementById("tablamia"), 15, 15, {
      //   width: 170,
      // });
      // doc.save("hola.pdf");
      doc.autoTable({
        html: "#reporte",
        theme: "striped",
        styles: { cellWidth: "auto", fontSize: 8, halign: "center" },
        bodyStyles: { fontSize: 8, halign: "left" },
      });
      doc.save("Informe de Cuentas.pdf");
      // doc.html('<p>hola</p>', {
      // callback: function (doc) {
      //   doc.save();
      //   },
      //   x: 10,
      //   y: 10
      // });
      // doc.fromHTML(document.getElementById("mypdf"), 20, 20, { width: 500 }); //<-- not good. How can I refactor this?
      // doc.save("mypdf.pdf");
      /** WITH CSS */
      // var canvasElement = document.createElement("canvas");
      // html2canvas(this.$refs.content, { canvas: canvasElement }).then(function (
      //   canvas
      // ) {
      //   const img = canvas.toDataURL("image/jpeg", 0.8);
      //   doc.addImage(img, "JPEG", 20, 20);
      //   doc.save("sample.pdf");
      // });
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