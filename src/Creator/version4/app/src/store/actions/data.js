import { rest } from '../../client/rest'

export const dataTypes = {
  LOAD_BACKGROUNDS: 'DATA_LOAD_BACKGROUNDS'
}

export const laodBackground = () => ({
  type: dataTypes.LOAD_BACKGROUNDS,
  payload: rest('backgrounds')
})

export default {
  loadBackground: laodBackground
}
