<template>
  <md-dialog-content>
    <md-dialog :md-active.sync="show" :md-click-outside-to-close="false">
      <md-toolbar class="md-primary">
        <h3 class="md-title">Elegir tratamiento</h3>
      </md-toolbar>

      <md-list style="overflow: auto;">
        <md-list-item v-for="(item, index) in tratamientos" v-bind:key="index + 'a'">
          <md-radio v-model="tratamiento" :value="item.id + '__' + item.titulo" />
          <span class="md-list-item-text">{{item.titulo}}</span>
        </md-list-item>
      </md-list>
      <md-dialog-actions>
        <md-button class="md-primary" @click="tratamientoNotSelected()">Cancelar</md-button>
        <md-button class="md-primary" @click="tratamientoSelected()">Guardar</md-button>
      </md-dialog-actions>
    </md-dialog>
  </md-dialog-content>
</template>

<script>
import TratamientoService from '@/services/Tratamiento'

export default {
  name: 'TratamientoPick',
  data: () => ({
    show: true,
    tratamientos: [],
    tratamiento: ''
  }),
  mounted () {
    this.getTratamientos()
  },
  methods: {
    async getTratamientos () {
      await TratamientoService.fetch().then((response) => {
        this.tratamientos = response.data
      })
    },
    tratamientoSelected () {
      let tratamiento = this.tratamiento.split('__')
      this.$emit('tratamiento-selected', { id: tratamiento[0], titulo: tratamiento[1] })
    },
    tratamientoNotSelected () {
      this.$emit('tratamiento-not-selected')
    }
  }
}
</script>

<style lang="scss" scoped>
.md-dialog /deep/.md-dialog-container {
  max-width: 768px;
}
</style>