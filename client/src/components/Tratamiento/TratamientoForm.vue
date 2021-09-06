<template>
  <form novalidate @submit.prevent="validateForm">

    <md-button type="submit" class="md-fab md-primary button-top" :disabled="sending">
      <md-icon>save</md-icon>
    </md-button>

    <md-button class="md-fab md-primary button-top2" v-on:click="goBack()">
      <md-icon>arrow_back</md-icon>
    </md-button>

    <div class="md-layout md-gutter">
      <md-field :class="getValidationClass('titulo')">
        <label for="titulo">Titulo</label>
        <md-input name="titulo" id="titulo" v-model="form.titulo" :disabled="sending" />
        <span class="md-error" v-if="!$v.form.titulo.required">Este campo es obligatorio.</span>
      </md-field>

      <md-field :class="getValidationClass('duracion')">
        <label for="duracion">Duración (en minutos)</label>
        <md-select name="duracion" id="duracion" v-model="form.duracion" md-dense :disabled="sending">
          <md-option value="15">15</md-option>
          <md-option value="30">30</md-option>
          <md-option value="45">45</md-option>
          <md-option value="60">60</md-option>
          <md-option value="75">75</md-option>
          <md-option value="90">90</md-option>
          <md-option value="120">105</md-option>
          <md-option value="120">120</md-option>
        </md-select>
        <span class="md-error">Este campo es obligatorio.</span>
      </md-field>

      <md-field :class="getValidationClass('precio')">
        <label for="precio">Precio</label>
        <md-input name="precio" id="precio" v-model="form.precio" :disabled="sending" />
        <span class="md-error" v-if="!$v.form.precio.required">Este campo es obligatorio.</span>
      </md-field>

      <md-field>
        <label>Descripción</label>
        <md-textarea name="descripcion" id="descripcion" v-model="form.descripcion"></md-textarea>
      </md-field>

    </div>

    <md-progress-bar md-mode="indeterminate" v-if="sending" />

  </form>
</template>

<script>
  import TratamientoService from '@/services/Tratamiento'
  import { validationMixin } from 'vuelidate'
  import {
    required
  } from 'vuelidate/lib/validators'

  export default {
    name: 'TratamientoForm',
    mixins: [validationMixin],
    data: () => ({
      title: 'Nuevo Tratamiento',
      form: {
        titulo: null,
        descripcion: null,
        precio: null,
        duracion: null
      },
      sending: false
    }),
    mounted () {
      this.$emit('set-title', 'Tratamiento')
      if (this.$route.params.id) {
        this.title = 'Modificar Tratamiento'
        this.getTratamiento()
      }
    },
    methods: {
      goBack () {
        this.$router.push({ name: 'Tratamientos', params: {} })
      },
      async getTratamiento () {
        await TratamientoService.get(this.$route.params.id)
        .then((tratamiento) => {
          let t = tratamiento.data[0]
          this.form.titulo = t.titulo
          this.form.descripcion = t.descripcion
          this.form.precio = t.precio
          this.form.duracion = t.duracion
          this.form.id = this.$route.params.id
        })
      },
      getValidationClass (fieldName) {
        const field = this.$v.form[fieldName]
        if (field) {
          return {
            'md-invalid': field.$invalid && field.$dirty
          }
        }
      },
      async saveForm () {
        this.sending = true
        if (this.form.id) {
          await TratamientoService.update(this.form).then(() => {
            this.sending = false
            this.$router.push({ name: 'Tratamientos', params: { saved: true } })
          })
        } else {
          await TratamientoService.add(this.form).then(() => {
            this.sending = false
            this.$router.push({ name: 'Tratamientos', params: { saved: true } })
          })
        }
      },
      validateForm () {
        this.$v.$touch()

        if (!this.$v.$invalid) {
          this.saveForm()
        }
      }
    },
    validations: {
      form: {
        titulo: {
          required
        },
        precio: {
          required
        },
        duracion: {
          required
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .md-progress-bar {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
  }
</style>
