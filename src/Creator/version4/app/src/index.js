import React from 'react'
import ReactDOM from 'react-dom'
import { Provider } from 'react-redux'
import { createGlobalStyle } from 'styled-components'

import App from './App'
import * as serviceWorker from './serviceWorker'
import store from './store'

import {rest} from './client/rest'
import {dispatch} from './client/dispatcher'

const GlobalStyle = createGlobalStyle`
 body {
  margin: 0;
  padding: 0;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen",
    "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue",
    sans-serif;
  }
  
  code {
    font-family: source-code-pro, Menlo, Monaco, Consolas, "Courier New", monospace;
  }
`


dispatch('firstTime=first&getCrePoint=get')
  .then(() => dispatch('setCP=1000&getCrePoint=get').then(rest('backgrounds')))


ReactDOM.render(
  <Provider store={store}>
    <React.Fragment>
      <GlobalStyle/>
      <App/>
    </React.Fragment>
  </Provider>
  , document.getElementById('root'))

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: http://bit.ly/CRA-PWA
serviceWorker.unregister()
