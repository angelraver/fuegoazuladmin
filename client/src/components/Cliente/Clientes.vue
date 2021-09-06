<template>
  <div>
    <md-autocomplete
      v-model="selectedCliente"
      :md-options="getSanitizedClientes"
      :md-open-on-focus="false"
      md-layout="box"
      @md-selected="onSelect"
    >
      <label>Buscar...</label>
      <template slot="md-autocomplete-item" slot-scope="{ item, term }">
        <md-highlight-text :md-term="term">{{ item.name }}</md-highlight-text>
      </template>
    </md-autocomplete>

    <md-button class="md-fab md-primary button-top" v-on:click="goToNew()">
      <md-icon>person_add</md-icon>
      <md-tooltip md-direction="bottom">Agregar cliente</md-tooltip>
    </md-button>

    <md-list class="md-double-line" v-if="clientes.length > 0" >

      <md-list-item v-for="(item, index) in clientes" v-bind:key="index" v-on:click="goToDetails(item.id)">
        <md-icon class="md-primary">person</md-icon>
        <div class="md-list-item-text">
          <span>{{ item.firstName }} {{ item.lastName }}</span>
          <span>{{ item.phone }}</span>
        </div>
      </md-list-item>

    </md-list>
    
    <h3 v-else>No hay clientes para listar.</h3>

    <md-snackbar :md-active.sync="this.$route.params.saved">Los datos se guardaron correctamente.</md-snackbar>
  </div>
</template>

<script>
import ClienteService from '@/services/Cliente'
export default {
  name: 'Clientes',
  computed: {
    getSanitizedClientes () {
      return this.clientes.map(cliente => ({
        'id': cliente.id,
        'name': cliente.firstName + ' ' + cliente.lastName,
        'toLowerCase': () => cliente.firstName.toLowerCase(),
        'toString': () => cliente.fistName
      }))
    }
  },
  data: () => ({
    clientes: [],
    selectedCliente: null
  }),
  mounted () {
    this.$emit('set-title', 'Clientes')
    this.getClientes()
  },
  methods: {
    async getClientes () {
      await ClienteService.fetch()
      .then((response) => {
        this.clientes = response.data
      })
    },
    onSelect (val) {
      this.goToDetails(val.id)
    },
    goToDetails (id) {
      this.$router.push({ name: 'ClienteDetails', params: { id } })
    },
    goToNew () {
      this.$router.push({ name: 'ClienteForm', params: { id: null } })
    }
  }
}
</script>
