import pipe from 'lodash/fp/pipe'
import groupBy from 'lodash/fp/groupBy'
import mapValues from 'lodash/fp/mapValues'

import typeToReducer from 'type-to-reducer'

import { dataTypes } from '../actions/ref-data'

const INITIAL_STATE = {
  loading: null,
  ai: {
    selected: null,
    data: {}
  },
  backgrounds: {
    selected: null,
    data: {}
  },
  morphs: {
    selected: null,
    data: {}
  },
  hardGear: {
    selected: null,
    data: {}
  },
  softGear: {
    selected: null,
    data: {}
  }
}

const indexByAtomUid = pipe(groupBy('atomUid'), mapValues(b => b[0]))

export const refData = typeToReducer({
  [dataTypes.LOAD_BACKGROUNDS]: {
    FULFILLED: (state, { payload }) => ({
      ...state,
      backgrounds: {
        selected: 'Atom_Hyperelite_5bc86b26b103c',
        data: indexByAtomUid(payload)
      }
    })
  },
  [dataTypes.LOAD_GEAR]: {
    FULFILLED: (state, { payload }) => ({
      ...state,
      ai: {
        selected: 'Atom_Animal_Keeper_Ai_5bc86b286ff0f',
        data: indexByAtomUid(payload.ai)
      },
      hardGear: {
        data: indexByAtomUid(payload.hardGear)
      },
      softGear: {
        selected: 'Atom_Active_Countermeasures_5bc86b287e331',
        data: indexByAtomUid(payload.softGear)
      }
    })
  },
  [dataTypes.LOAD_MORPHS]: {
    FULFILLED: (state, { payload }) => ({
      ...state,
      morphs: {
        data: indexByAtomUid(payload)
      }
    })
  },
  [dataTypes.LOAD_ALL_REF_DATA]: {
    FULFILLED: (state, { payload }) => ({
      ...state,
      loading: 'fulfilled'
    }),
    PENDING: (state, { payload }) => ({
      ...state,
      loading: 'pending'
    }),

  }
}, INITIAL_STATE)

export default refData
