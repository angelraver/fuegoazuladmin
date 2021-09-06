<template>
  <div>
    <div class="logo">
      <img src="/static/images/logo-l.png" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'Landing',
  mounted () {
    this.$emit('set-title', 'Fuego Azul Administración')
  }
}
</script>
<style lang="scss" scoped>
.logo {
  display: block;
  width: 100%;
  text-align: center;
}
</style>


