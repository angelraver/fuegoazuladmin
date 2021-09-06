const months = [
  'Enero',
  'Febrero',
  'Marzo',
  'Abril',
  'Mayo',
  'Junio',
  'Julio',
  'Agosto',
  'Septiembre',
  'Octubre',
  'Noviembre',
  'Diciembre'
]
const getMesNombre = (month) => {
  return months[Math.abs(month) - 1]
}
const weekday = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']
const dateObj = (fecha) => {
  let fechap
  if (fecha.indexOf('/') > 0) {
    fechap = fecha.split('/')
    return {
      day: fechap[0],
      month: fechap[1],
      year: fechap[2]
    }
  }
  if (fecha.indexOf('-') > 0) {
    fechap = fecha.split('-')
    return {
      day: fechap[2],
      month: fechap[1],
      year: fechap[0]
    }
  }
}
const getDayNombre = (date) => {
  var dateFormated = new Date(date + 'T10:00:00Z')
  return weekday[dateFormated.getDay()]
}

export default {
  dateObj (date) {
    return dateObj(date)
  },
  getDayNombre (date) {
    return getDayNombre(date)
  },
  getMesNombre (month) {
    return getMesNombre(month)
  },
  getDay (day) {
    return Math.abs(day)
  },
  label (date) {
    const fecha = dateObj(date)
    return getDayNombre(date) + ' ' + Math.abs(fecha.day) + ' de ' + getMesNombre(fecha.month)
  },
  labelS (date) {
    const fecha = dateObj(date)
    return Math.abs(fecha.day) + '/' + Math.abs(fecha.month) + '/' + fecha.year
  }
}
