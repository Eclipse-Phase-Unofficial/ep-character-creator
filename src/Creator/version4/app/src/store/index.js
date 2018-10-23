import { createStore, applyMiddleware, combineReducers } from 'redux'
import thunk from 'redux-thunk'
import promiseMiddleware from 'redux-promise-middleware'
import { composeWithDevTools } from 'redux-devtools-extension'

import * as reducers from './reducers'

let middlewares = applyMiddleware(
  thunk,
  promiseMiddleware()
)

if (process.env.NODE_ENV !== 'prod') {
  middlewares = composeWithDevTools(middlewares)
}

const store = createStore(
  combineReducers({
    ...reducers
  }),
  middlewares
)

export default store
