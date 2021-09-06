<template>
  <div class="tratamientos">
    <md-button class="md-fab md-primary button-top" v-on:click="goToNew()">
      <md-icon>add_circle</md-icon>
      <md-tooltip md-direction="bottom">Agregar tratamiento</md-tooltip>
    </md-button>

    <md-list class="md-double-line" >
      <div v-for="(item, index) in tratamientos" v-bind:key="index">
        <md-list-item>
          <div class="md-list-item-text">
            <span>{{ item.titulo }}</span>
            <span>$ {{ item.precio }} - {{ item.duracion }} min</span>
            <p>{{ item.descripcion }}</p>
          </div>

          <md-button class="md-icon-button md-list-action" v-on:click="goToEdit(item.id)">
            <md-icon>edit</md-icon>
          </md-button>
        </md-list-item>
        <md-divider class="md-inset"></md-divider>
      </div>
    </md-list>

    <div v-if="tratamientos.length < 1">
      <p>No hay tratamientos para listar.</p>
    </div>

    <md-snackbar :md-active.sync="this.$route.params.saved">Los datos se guardaron correctamente.</md-snackbar>

  </div>
</template>

<script>
import TratamientoService from '@/services/Tratamiento'
export default {
  name: 'Tratamientos',
  data: () => ({
    tratamientos: []
  }),
  mounted () {
    this.$emit('set-title', 'Tratamientos')
    this.getTratamientos()
  },
  methods: {
    async getTratamientos () {
      await TratamientoService.fetch()
      .then((response) => {
        this.tratamientos = response.data
      })
    },
    goToEdit (id) {
      this.$router.push({ name: 'TratamientoForm', params: { id } })
    },
    goToNew () {
      this.$router.push({ name: 'TratamientoForm', params: { id: null } })
    }
  }
}
</script>
