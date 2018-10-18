import { createStore, applyMiddleware } from 'redux'
import thunk from 'redux-thunk'
import promiseMiddleware from 'redux-promise-middleware'
import reducers from '/reducers'

export default createStore(
  reducers,
  // r() tells createStore() how to handle middleware
  applyMiddleware(thunk, promiseMiddleware())
)
