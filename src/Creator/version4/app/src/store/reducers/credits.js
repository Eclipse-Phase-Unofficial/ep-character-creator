import typeToReducer from 'type-to-reducer'

import { creditTypes } from '../actions/credits'
import { appContextTypes } from '../actions/appContext'

const INITIAL_STATE = {
  creation: 0,
  credit: 0,
  aptitude: 0,
  reputation: 0,
  rez: 0,
  asr: 0,
  ksr: 0
}

const loadCredits = (state, { payload }) => ({
  ...state,
  creation: payload['creation_remain'],
  credit: payload['credit_remain'],
  aptitude: payload['aptitude_remain'],
  reputation: payload['reputation_remain'],
  rez: payload['rez_remain'],
  asr: payload['asr_remain'],
  ksr: payload['ksr_remain']
})

export const credits = typeToReducer({
  [creditTypes.SET_CP]: {
    FULFILLED: loadCredits
  },
  [appContextTypes.SESSION_CHECK]: {
    FULFILLED: loadCredits
  }
}, INITIAL_STATE)

export default credits
