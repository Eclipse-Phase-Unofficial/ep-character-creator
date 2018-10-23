import dispatcher from '../../client/dispatcher'

export const creditTypes = {
  SET_CP: 'CREDITS_SET_CP'
}

const setCP = (cpAmount = 1000) => ({
  type: creditTypes.SET_CP,
  payload: dispatcher.dispatch(`setCP=${cpAmount}&getCrePoint=get`)
})

export default {
  setCP
}
