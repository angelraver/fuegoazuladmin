<template>
  <div>
    <md-card md-with-hover v-for="(turnosFecha, index) in turnos" v-bind:key="index">
      <md-ripple>
        <md-card-header>
          <div class="md-title">{{ dateLabel(turnosFecha[0][0].fecha) }}</div>
        </md-card-header>
        <md-card-content>
          <md-list class="md-double-line">
            <md-list-item v-for="(turnosHora, index) in turnosFecha" v-bind:key="index" class="hora-line">
              <span class="hora">
                {{ horaStr(turnosHora[0].hora) }}
              </span>
              <div class="avatar-line">
                <md-avatar
                  v-for="(turno, index) in turnosHora" v-bind:key="index"
                  @click.native="turnoDetail(turno)"
                  class="md-avatar-icon client-initials md-accent"
                >
                  {{ initial(turno.firstName) + initial(turno.lastName) }}
                </md-avatar>
              </div>

            </md-list-item>
          </md-list>
        </md-card-content>
      </md-ripple>
    </md-card>

    <md-button class="md-fab md-primary button-top" v-on:click="newTurno()">
      <md-icon>add_alarm</md-icon>
      <md-tooltip md-direction="bottom">Agregar turno</md-tooltip>
    </md-button>

    <md-dialog-confirm
      :md-active.sync="showTurnoDetailDialog"
      md-title="Turno"
      :md-content="turnoDetailText"
      md-confirm-text="¿Cancelar?"
      md-cancel-text="Ok"
      @md-confirm="goToCancelTurno"
    />
    <md-dialog-confirm
      :md-active.sync="showCancelDialog"
      md-title="¿Cancelar Turno?"
      :md-content="cancelText"
      md-confirm-text="Sí"
      md-cancel-text="No"
      @md-confirm="cancelTurno"
    />
  </div>
</template>

<script>
import TurnoService from '@/services/Turno'
import MT from '@/utils/miniTemplates.js'
import D from '@/utils/date'
import H from '@/utils/hora'
import L from '@/utils/layout.js'
import _ from 'lodash'

export default {
  name: 'TurnosLanding',
  data: () => ({
    turnos: [],
    showTurnoDetailDialog: false,
    turnoDetailText: '',
    turnoToCancel: {},
    idTurnoCancelled: null,
    showCancelDialog: false,
    cancelText: ''
  }),
  watch: {
    idTurnoCancelled: function () {
      this.getTurnos()
    },
    showTurnoDetailDialog: function (val) {
      L.hideNav(val)
    },
    showCancelDialog: function (val) {
      L.hideNav(val)
    }
  },
  mounted () {
    this.getTurnos()
    this.$emit('set-title', 'Turnos')
  },
  methods: {
    newTurno () {
      this.$router.push({ name: 'Clientes' })
    },
    async getTurnos () { // this returns the format [Dias [Horas [Turnos]]]
      await TurnoService.fetch({ status: 'activo', next: 'ok', get: true })
      .then((response) => {
        const fechas = _.uniq(response.data.map(t => t.fecha))
        const turnos = fechas.map(fecha => response.data.filter(turno => turno.fecha === fecha))
        const turnosHorasBlock = turnos.map((turnoFecha) => {
          const horaBlock = _.uniq(turnoFecha.map(t => t.hora))
          return horaBlock.map((hora) => {
            return turnoFecha.filter(turno => turno.hora === hora)
          })
        })
        this.turnos = turnosHorasBlock
      })
    },
    turnoDetail (turno) {
      this.showTurnoDetailDialog = true
      this.turnoDetailText = MT.turnoDetails(turno)
      this.turnoToCancel = turno
    },
    goToCancelTurno () {
      this.showTurnoDetailDialog = false
      this.showCancelDialog = true
      this.cancelText = MT.turnoDetails(this.turnoToCancel)
    },
    async cancelTurno () {
      const turno = {
        id: this.turnoToCancel.id,
        status: 'eliminado'
      }
      await TurnoService.update(turno).then(() => {
        this.idTurnoCancelled = this.turnoToCancel.id
      })
    },
    initial (text) {
      return text ? text[0] : ''
    },
    dateLabel (date) {
      return D.label(date)
    },
    horaStr (hora) {
      return H.horaToStr(hora)
    }
  }
}
</script>

<style lang="scss" scoped>
.hora-line {
  margin-bottom: .5em;
  border-top: 1px solid #e8e8e8;
}
.md-card {
  margin-bottom: 1em;
}
.avatar-line {
  width: 100%;
  text-align: center;
}
</style>