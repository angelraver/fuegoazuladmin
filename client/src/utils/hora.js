export default {
  horaToStr (hora) {
    return String(hora)
      .replace('000', '0:00')
      .replace('00', ':00')
      .replace('::', ':')
  }
}
