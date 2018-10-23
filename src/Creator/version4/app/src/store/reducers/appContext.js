import typeToReducer from 'type-to-reducer'

import { appContextTypes } from '../actions/appContext'

const INITIAL_STATE = {
  error: false,
  sessionExist: false
}

export const appContext = typeToReducer({
  [appContextTypes.SESSION_CHECK]: {
    FULFILLED: (state, { payload }) => ({
      ...state,
      sessionExist: payload.sessionExist
    }),
    REJECTED: (state, action) => ({
      ...state,
      error: true,
      sessionExist: false
    })
  }
}, INITIAL_STATE)

export default appContext
