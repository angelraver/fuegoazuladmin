import D from '@/utils/date'
import H from '@/utils/hora'

export default {
  turnoDetails (turno) {
    return `
      <h1>${turno.firstName} ${turno.lastName}</h1>
      <h2>${D.label(turno.fecha)} a las ${H.horaToStr(turno.hora)}</h2>
      <h3>${turno.titulo}</h3>`
  }
}
