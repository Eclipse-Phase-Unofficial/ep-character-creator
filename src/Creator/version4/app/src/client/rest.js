import common from './common'

const BASE_URL = `${common.baseUrl}${common.path}/rest`
console.log('BASE_URL')
console.log(BASE_URL)

const DEFAULT_HEADERS = {
  'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
}

export const rest = endpoint => () => fetch(
  `${BASE_URL}/${endpoint}.php`,
  {
    method: 'GET',
    headers: DEFAULT_HEADERS,
    credentials: 'include',
  })
  .then(r => r.text())
  .then(console.log)
  .catch(console.error)

