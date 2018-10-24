import common from './common'

const BASE_URL = `${common.baseUrl}${common.path}/scripts/dispatcher.php`

const DEFAULT_HEADERS = {
  'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
}

export const dispatch = body => fetch(
  BASE_URL,
  {
    method: 'POST',
    headers: DEFAULT_HEADERS,
    // mode: 'cors',
    credentials: 'include',
    body
  })
  .then(r => r.json())
  .then(r => { console.debug(r); return r })
  .catch(console.error)

export default {
  dispatcher: dispatch
}
