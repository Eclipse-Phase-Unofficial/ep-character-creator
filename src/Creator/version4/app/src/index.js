import 'carbon-icons'

import React from 'react'
import ReactDOM from 'react-dom'
import { Provider } from 'react-redux'
import { createGlobalStyle, ThemeProvider } from 'styled-components'

import App from './App'
import * as serviceWorker from './serviceWorker'
import store from './store'
import contextActions from './store/actions/appContext'
import dataActions from './store/actions/ref-data'
import { theme } from './theme'

const GlobalStyle = createGlobalStyle`
 body {
  margin: 0;
  padding: 0;
  background-color: #f5f7fa;
}
`

store.dispatch(contextActions.checkExistingSession())
store.dispatch(dataActions.loadAllRefData())

ReactDOM.render(
  <Provider store={store}>
    <ThemeProvider theme={theme}>
      <React.Fragment>
        <GlobalStyle />
        <App />
      </React.Fragment>
    </ThemeProvider>
  </Provider>
  , document.getElementById('root'))

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: http://bit.ly/CRA-PWA
serviceWorker.unregister()
