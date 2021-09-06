<template>
  <div>
    <section>
      <h1 class="md-title">{{ firstName }} {{ lastName }}</h1>
      <div v-if="phone">
        <a :href="'tel:' + phone" >
          <img src="/static/images/wasap.png" />
          {{ phone }}
        </a>
      </div>
      <p>{{ gender === 'M' ? 'Mujer' : 'Hombre' }}</p>
      <p>{{ descripcion }}</p>
      <md-button class="md-fab md-primary button-top" v-on:click="newTurno()">
        <md-icon>add_alarm</md-icon>
        <md-tooltip md-direction="bottom">Agregar turno</md-tooltip>
      </md-button>
    </section>
    <section class="v_buttons_block">
      <md-button class="md-fab md-primary button-bottom" v-on:click="showDeleteDialog = true">
        <md-icon>delete</md-icon>
        <md-tooltip md-direction="bottom">Eliminar cliente</md-tooltip>
      </md-button>
      <md-button class="md-fab md-primary button-bottom" v-on:click="goToEdit()">
        <md-icon>edit</md-icon>
        <md-tooltip md-direction="bottom">Editar cliente</md-tooltip>
      </md-button>
    </section>

    <md-card v-if="turnos.length > 0">
      <md-card-header>
        <div class="md-title">Turnos Activos</div>
      </md-card-header>
      <md-card-content>
        <md-list class="md-double-line md-dense" v-if="turnos.length > 0">
          <md-list-item v-for="(item, index) in turnos" v-bind:key="index">
            <div class="md-list-item-text">
              <span>{{ horaStr(item.hora) }}</span>
              <span>{{ dateLabel(item.fecha) }} de {{ dateObj(item.fecha).year }}</span>
              <span>{{ item.titulo }}</span>
              <strong>{{ item.firstName }}</strong>
            </div>
            <md-button class="md-icon-button md-list-action" v-on:click="turnoToCancel(item)">
              <md-icon class="md-primary">cancel</md-icon>
            </md-button>
          </md-list-item>
        </md-list>
      </md-card-content>
    </md-card>
    <br />
   <md-list class="md-double-line md-dense" v-if="turnos.length > 0">
      <md-subheader>Historial</md-subheader>
      <md-list-item v-for="(item, index) in turnosHistorial" v-bind:key="index">
        <div class="md-list-item-text">
          <span>{{ item.titulo }}</span>
          <span>{{ dateLabel(item.fecha) }} de {{ dateObj(item.fecha).year }}</span>
        </div>
      </md-list-item>
    </md-list>

    <md-dialog-confirm
      :md-active.sync="showDeleteDialog"
      md-title="Eliminar Cliente"
      md-content="Estás a punto de eliminar éste cliente."
      md-confirm-text="Aceptar"
      md-cancel-text="Cancelar"
      @md-confirm="deleteCliente"
    />

    <md-dialog-confirm
      :md-active.sync="showCancelDialog"
      md-title="Cancelar Turno"
      :md-content="cancelText"
      md-confirm-text="Aceptar"
      md-cancel-text="Cancelar"
      @md-confirm="cancelTurno"
    />

    <md-snackbar :md-active.sync="this.$route.params.saved">Los datos se guardaron correctamente.</md-snackbar>
  </div>
</template>

<script>
import ClienteService from '@/services/Cliente'
import TurnoService from '@/services/Turno'
import D from '@/utils/date'
import MT from '@/utils/miniTemplates.js'
import L from '@/utils/layout.js'
import H from '@/utils/hora'

export default {
  name: 'ClienteDetails',
  mounted () {
    this.$emit('set-title', 'Detalle de cliente')
    this.idCliente = this.$route.params.id
    if (this.idCliente) {
      this.getCliente()
      this.getTurnosCliente()
      this.getTurnosClienteHistorial()
    }
  },
  data: () => ({
    idCliente: null,
    firstName: '',
    lastName: '',
    phone: '',
    gender: '',
    email: '',
    descripcion: '',
    showDeleteDialog: false,
    turnos: [],
    turnosHistorial: [],
    showCancelDialog: false,
    cancelText: ''
  }),
  watch: {
    showDeleteDialog: function (val) {
      L.hideNav(val)
    },
    showCancelDialog: function (val) {
      L.hideNav(val)
    }
  },
  methods: {
    async getCliente () {
      await ClienteService.get(this.idCliente)
      .then((response) => {
        let c = response.data[0]
        this.firstName = c.firstName
        this.lastName = c.lastName
        this.phone = c.phone
        this.gender = c.gender
        this.email = c.email
        this.descripcion = c.descripcion
      })
    },
    async getTurnosCliente () {
      await TurnoService.fetch({
        idCliente: this.idCliente,
        status: 'activo',
        get: true,
        next: 'ok'
      }).then((response) => {
        this.turnos = response.data
      })
    },
    async getTurnosClienteHistorial () {
      await TurnoService.fetch({
        idCliente: this.idCliente,
        get: true,
        next: 'ko'
      }).then((response) => {
        this.turnosHistorial = response.data
      })
    },
    turnoToCancel (turno) {
      this.showCancelDialog = true
      this.cancelText = MT.turnoDetails(turno)
      this.idTurnoToCancel = turno.id
    },
    async cancelTurno () {
      const turno = {
        id: this.idTurnoToCancel,
        status: 'eliminado'
      }
      await TurnoService.update(turno).then(() => {
        this.getTurnosCliente()
      })
    },
    confirmDelete () {
      this.showDeleteDialog = true
      document.getElementsByClassName('md-drawer')[0].style['z-index'] = '5'
    },
    async deleteCliente () {
      await ClienteService.delete(this.idCliente).then(() => {
        this.$router.push({ name: 'Clientes', params: { saved: true } })
      })
    },
    goToEdit () {
      this.$router.push({ name: 'ClienteForm', params: { id: this.idCliente } })
    },
    newTurno () {
      this.$router.push({ name: 'TurnoForm', params: { idCliente: this.idCliente } })
    },
    dateLabel (date) {
      return D.label(date)
    },
    dateObj (date) {
      return D.dateObj(date)
    },
    horaStr (hora) {
      return H.horaToStr(hora)
    }
  }
}
</script>
