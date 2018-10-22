import _ from 'lodash'
import typeToReducer from 'type-to-reducer'

import { dataTypes } from '../actions/data'

const INITIAL_STATE = {
  backgrounds: {
    selected: null,
    data: []
  }
}

export const data = typeToReducer({
  [dataTypes.LOAD_BACKGROUNDS]: {
    FULFILLED: (state, {payload}) => ({
      ...state,
      backgrounds:{
        selected: "Atom_Hyperelite_5bc86b26b103c",
        data: _(payload).groupBy('atomUid').mapValues(b => b[0]).value()
      }
    })
  }
}, INITIAL_STATE)

export default data
