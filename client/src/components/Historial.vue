<template>
  <div>
    <md-table v-model="turnos">
      <md-table-row slot="md-table-row" slot-scope="{ item }">
        <md-table-cell md-label="Fecha" md-sort-by="fecha">{{ fechaS(item.fecha) }}</md-table-cell>
        <md-table-cell md-label="Hora" md-sort-by="hora">{{ horaStr(item.hora) }}</md-table-cell>
        <md-table-cell md-label="Cliente" md-sort-by="firstName">{{ item.firstName }} {{ item.lastName }}</md-table-cell>
        <md-table-cell md-label="Tratamiento" md-sort-by="titulo">{{ item.titulo }}</md-table-cell>
        <md-table-cell md-label="Precio" md-sort-by="precio">$ {{ item.precio }}</md-table-cell>
      </md-table-row>
    </md-table>
  </div>
</template>

<script>
import TurnoService from '@/services/Turno'
import D from '@/utils/date'
import H from '@/utils/hora'
export default {
  name: 'Historial',
  data: () => ({
    turnos: []
  }),
  mounted () {
    this.getTurnos()
    this.$emit('set-title', 'Historial')
  },
  methods: {
    async getTurnos () {
      await TurnoService.fetch({ status: 'activo', next: '', get: true })
      .then((response) => {
        this.turnos = response.data
      })
    },
    horaStr (hora) {
      return H.horaToStr(hora)
    },
    fechaS (fecha) {
      return D.labelS(fecha)
    }
  }
}
</script>
