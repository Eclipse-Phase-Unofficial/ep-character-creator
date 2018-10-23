import { dispatch } from '../../client/dispatcher'

export const appContextTypes = {
  SESSION_CHECK: 'APP_CONTEXT_SESSION_CHECK'
}

const checkExistingSession = () => ({
  type: appContextTypes.SESSION_CHECK,
  payload: dispatch('firstTime=first&getCrePoint=get')
})

export default {
  checkExistingSession
}
