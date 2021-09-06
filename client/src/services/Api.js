import axios from 'axios'

export default() => {
  const api = process.env.API
  return axios.create({ baseURL: api })
}
