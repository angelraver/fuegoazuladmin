<template>
  <form novalidate @submit.prevent="validateForm">
    <md-button type="submit" class="md-fab md-primary button-top" :disabled="sending">
      <md-icon>save</md-icon>
    </md-button>
    <md-button class="md-fab md-primary button-top2" v-on:click="goBack()">
      <md-icon>arrow_back</md-icon>
    </md-button>

    <md-field :class="getValidationClass('firstName')">
      <label for="first-name">Nombre</label>
      <md-input name="first-name" id="first-name" v-model="form.firstName" :disabled="sending" />
      <span class="md-error" v-if="!$v.form.firstName.required">Este campo es obligatorio.</span>
    </md-field>

    <md-field :class="getValidationClass('lastName')">
      <label for="last-name">Apellido</label>
      <md-input name="last-name" id="last-name" v-model="form.lastName" :disabled="sending" />
      <span class="md-error" v-if="!$v.form.lastName.required">Este campo es obligatorio.</span>
      <span class="md-error" v-else-if="!$v.form.lastName.minlength">Invalid last name</span>
    </md-field>

    <md-field :class="getValidationClass('phone')">
      <label for="phone">Celular</label>
      <md-input name="phone" id="phone" v-model="form.phone" :disabled="sending" />
      <span class="md-error" v-if="!$v.form.phone.required">Este campo es obligatorio.</span>
    </md-field>

    <md-field :class="getValidationClass('gender')">
      <label for="gender">Género</label>
      <md-select name="gender" id="gender" v-model="form.gender" md-dense :disabled="sending">
        <md-option></md-option>
        <md-option value="M">Mujer</md-option>
        <md-option value="H">Hombre</md-option>
      </md-select>
      <span class="md-error">Este campo es obligatorio.</span>
    </md-field>

    <md-field :class="getValidationClass('email')">
      <label for="first-email">Email</label>
      <md-input name="email" id="email" v-model="form.email" :disabled="sending" />
      <span class="md-error">Email mal escrito.</span>
    </md-field>

    <md-field>
      <label>Notas</label>
      <md-textarea name="descripcion" id="descripcion" v-model="form.descripcion"></md-textarea>
    </md-field>

    <md-progress-bar md-mode="indeterminate" v-if="sending" />

  </form>
</template>

<script>
  import ClienteService from '@/services/Cliente'
  import { validationMixin } from 'vuelidate'
  import {
    required,
    email
  } from 'vuelidate/lib/validators'

  export default {
    name: 'ClienteForm',
    mixins: [validationMixin],
    data: () => ({
      title: 'Nuevo Cliente',
      form: {
        firstName: null,
        lastName: null,
        phone: null,
        gender: 'M',
        email: null,
        descripcion: null
      },
      sending: false
    }),
    mounted () {
      if (this.$route.params.id) {
        this.title = 'Editar  Cliente'
        this.getCliente()
      }
      this.$emit('set-title', this.title)
    },
    methods: {
      goBack () {
        this.$router.push({ name: 'Clientes', params: {} })
      },
      async getCliente () {
        await ClienteService.get(this.$route.params.id)
        .then((response) => {
          let c = response.data[0]
          this.form.firstName = c.firstName
          this.form.lastName = c.lastName
          this.form.phone = c.phone
          this.form.gender = c.gender
          this.form.email = c.email
          this.form.descripcion = c.descripcion
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
          await ClienteService.update(this.form).then(() => {
            this.sending = false
            this.$router.push({ name: 'ClienteDetails', params: { id: this.form.id, saved: true } })
          })
        } else {
          await ClienteService.add(this.form).then(() => {
            this.sending = false
            this.$router.push({ name: 'Clientes', params: { saved: true } })
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
        firstName: {
          required
        },
        lastName: {
          required
        },
        phone: {
          required
        },
        gender: {
          required
        },
        email: {
          email
        }
      }
    }
  }
</script>
