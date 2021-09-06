<template>
  <form>
    <h2>Nuevo turno para {{cliente.firstName}} {{cliente.lastName}}</h2>
    <div class="md-layout md-gutter">
      <md-datepicker
        v-model="selectedDate" 
        :md-disabled-dates="disabledDates"
        md-immediately
      >
        <label>Elige la fecha</label>
      </md-datepicker>
    </div>
    <turnos-horas
      v-if="selectedDate"
      :fecha="dateFormated"
      :id-turno-cancelled="idTurnoCancelled"
      @hora-selected="horaSelected"
      @turno-to-cancel="turnoToCancel"
    />

    <tratamiento-pick v-if="showTratamientos" @tratamiento-selected="tratamientoSelected" @tratamiento-not-selected="tratamientoNotSelected" />

    <md-dialog-confirm
      :md-active.sync="showCancelDialog"
      md-title="¿Cancelar Turno?"
      :md-content="cancelText"
      md-confirm-text="Sí"
      md-cancel-text="No"
      @md-confirm="cancelTurno"
    />

  </form>
</template>

<script>
import ClienteService from '@/services/Cliente'
import TurnoService from '@/services/Turno'
import TratamientoPick from '@/components/Tratamiento/TratamientoPick'
import TurnosHoras from '@/components/Turno/TurnosHoras'
import format from 'date-fns/format'
import MT from '@/utils/miniTemplates.js'

export default {
  name: 'TurnoForm',
  components: { TratamientoPick, TurnosHoras },
  mounted () {
    this.$emit('set-title', 'Crear turno')
    if (this.$route.params.idCliente) this.getCliente()
    this.$material.locale.dateFormat = this.dateFormat
  },
  data: () => ({
    nuevoTurno: {},
    cliente: {},
    cancelText: '',
    idTurnoToCancel: null,
    idTurnoCancelled: null,
    showTratamientos: false,
    showCancelDialog: false,
    dateFormat: 'dd/MM/yyyy',
    dateFormatDB: 'yyyy-MM-dd',
    selectedDate: null,
    disabledDates: date => {
      const day = date.getDay()
      return day === 0
    }
  }),
  computed: {
    dateFormated () {
      return format(this.selectedDate, this.dateFormat)
    },
    dateFormatedDB () {
      return format(this.selectedDate, this.dateFormatDB)
    }
  },
  methods: {
    async getCliente () {
      await ClienteService.get(this.$route.params.idCliente)
      .then((cliente) => {
        const data = cliente.data[0]
        this.cliente = data
        this.nuevoTurno.idCliente = data.id
      })
    },
    horaSelected (hora) {
      this.showTratamientos = true
      this.nuevoTurno.fecha = this.dateFormatedDB
      this.nuevoTurno.hora = hora
      // this.nuevoTurno.raw = this.selectedDate
    },
    async tratamientoNotSelected () {
      this.showTratamientos = false
    },
    async tratamientoSelected (tratamiento) {
      this.showTratamientos = false
      this.nuevoTurno.idTratamiento = tratamiento.id
      await TurnoService.add(this.nuevoTurno).then(() => {
        this.$router.push({ name: 'ClienteDetails', params: { id: this.nuevoTurno.idCliente } })
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
        this.idTurnoCancelled = this.idTurnoToCancel
      })
    }
  }
}
</script>
 