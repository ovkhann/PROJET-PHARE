// _services/CallerService.ts
import axios from 'axios'

const Caller = axios.create({
  baseURL: 'http://localhost:8000', // ton API Laravel
  withCredentials: true,            // nécessaire pour Sanctum
  headers: {
    Accept: 'application/json',
  },
  withXSRFToken: true,              // Axios gère le XSRF token
  xsrfCookieName: 'XSRF-TOKEN',     // nom du cookie envoyé par Laravel
  xsrfHeaderName: 'X-XSRF-TOKEN'    // nom du header envoyé par Axios
})

export default Caller
