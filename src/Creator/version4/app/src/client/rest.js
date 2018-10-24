import common from './common'

const BASE_URL = `${common.baseUrl}${common.path}/rest`

const DEFAULT_HEADERS = {
  'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
}

export const rest = endpoint => fetch(
  `${BASE_URL}/${endpoint}.php`,
  {
    method: 'GET',
    headers: DEFAULT_HEADERS,
    credentials: 'include'
  })
  .then(r => r.json())
  .then(r => { console.debug(r); return r })
  .catch(console.error)
