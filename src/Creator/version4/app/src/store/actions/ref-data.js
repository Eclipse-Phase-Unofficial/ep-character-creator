import pipe from 'lodash/fp/pipe'
import flatMap from 'lodash/fp/flatMap'
import values from 'lodash/fp/values'

import { rest } from '../../client/rest'

export const dataTypes = {
  LOAD_ALL_REF_DATA: 'DATA_LOAD_REF_DATA',

  LOAD_BACKGROUNDS: 'DATA_LOAD_BACKGROUNDS',
  LOAD_CREDIT: 'DATA_LOAD_CREDIT',
  LOAD_FACTIONS: 'DATA_LOAD_FACTIONS',
  LOAD_MORPHS: 'DATA_LOAD_MORPHS',
  LOAD_MOTIVATION: 'DATA_LOAD_MOTIVATION',
  LOAD_REPUTATIONS: 'DATA_LOAD_REPUTATIONS',
  LOAD_GEAR: 'DATA_LOAD_GEAR'
}

export const loadBackgrounds = () => ({
  type: dataTypes.LOAD_BACKGROUNDS,
  payload: rest('backgrounds')
})

export const loadCredit = () => ({
  type: dataTypes.LOAD_CREDIT,
  payload: rest('credit')
})

export const loadFactions = () => ({
  type: dataTypes.LOAD_FACTIONS,
  payload: rest('factions')
})

export const loadGear = () => ({
  type: dataTypes.LOAD_GEAR,
  payload: rest('gear')
})

export const loadMorphs = () => ({
  type: dataTypes.LOAD_MORPHS,
  payload: rest('morphs')
})

let actions = {
  loadBackgrounds,
  loadCredit,
  loadFactions,
  loadGear,
  loadMorphs
}

const mapDispatch = dispatch => flatMap(action => dispatch(action()))
const triggerAllActions = dispatch => () => pipe(values, mapDispatch(dispatch))(actions)

const dispatchActions = (actions = []) => ({
  type: dataTypes.LOAD_ALL_REF_DATA,
  payload: Promise.all([...actions])
})

const loadAllRefData = () => dispatch => (pipe(triggerAllActions(dispatch), dispatchActions, dispatch)())

export default {
  ...actions,
  loadAllRefData
}
