// Adresa backend API-ja. Postavlja se varijablom VITE_API_URL u frontend/.env
// (vidi .env.example); bez nje se koristi lokalni Laravel server.
export const API_URL: string = (
  import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api'
).replace(/\/+$/, '')
